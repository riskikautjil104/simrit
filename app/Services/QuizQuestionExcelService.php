<?php

namespace App\Services;

use App\Models\QuizQuestion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuizQuestionExcelService
{
    public const HEADERS = [
        'Pertanyaan',
        'Pilihan A',
        'Pilihan B',
        'Pilihan C',
        'Pilihan D',
        'Jawaban Benar',
        'Poin',
        'Urutan',
        'Waktu (detik)',
        'Status',
    ];

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Soal');

        foreach (self::HEADERS as $col => $header) {
            $cell = $sheet->getCell([$col + 1, 1]);
            $cell->setValue($header);
        }

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDBEAFE');

        $sheet->fromArray([
            'Apa kepanjangan dari SIMRS?',
            'Sistem Informasi Manajemen Rumah Sakit',
            'Sistem Integrasi Medis Rumah Sakit',
            'Sistem Informasi Medis Rumah Sakit',
            'Sistem Internal Manajemen RS',
            'a',
            1,
            1,
            60,
            'active',
        ], null, 'A2');

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $instructions = $spreadsheet->createSheet();
        $instructions->setTitle('Panduan');
        $instructions->setCellValue('A1', 'Panduan Pengisian Template Soal');
        $instructions->getStyle('A1')->getFont()->setBold(true);
        $instructions->fromArray([
            ['Kolom', 'Wajib', 'Keterangan'],
            ['Pertanyaan', 'Ya', 'Teks pertanyaan soal'],
            ['Pilihan A–D', 'Ya', 'Empat pilihan jawaban'],
            ['Jawaban Benar', 'Ya', 'Isi dengan a, b, c, atau d (huruf kecil)'],
            ['Poin', 'Tidak', 'Angka, default 1 jika kosong'],
            ['Urutan', 'Tidak', 'Angka urutan tampil, otomatis jika kosong'],
            ['Waktu (detik)', 'Tidak', '5–600 detik per soal, kosong = pakai default kuis'],
            ['Status', 'Tidak', 'active atau draft, default active'],
            ['', '', ''],
            ['Catatan', '', 'Baris contoh di sheet Template Soal boleh dihapus sebelum upload.'],
            ['', '', 'Saat import, pilih kuis tujuan di halaman admin (opsional).'],
        ], null, 'A2');
        $instructions->getColumnDimension('A')->setWidth(18);
        $instructions->getColumnDimension('B')->setWidth(8);
        $instructions->getColumnDimension('C')->setWidth(55);

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 'Template-Soal-Kuis.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return array{imported: int, errors: array<int, string>}
     */
    public function import(UploadedFile $file, ?int $quizId, int $userId): array
    {
        $rows = $this->parseRows($file);

        if (empty($rows)) {
            return [
                'imported' => 0,
                'errors' => [0 => 'File tidak berisi data soal. Pastikan ada baris di bawah header.'],
            ];
        }

        $imported = 0;
        $errors = [];

        DB::transaction(function () use ($rows, $quizId, $userId, &$imported, &$errors) {
            $nextSortOrder = (int) (QuizQuestion::query()
                ->when($quizId, fn ($q) => $q->where('quiz_id', $quizId))
                ->max('sort_order') ?? 0);

            foreach ($rows as $row) {
                $validated = $this->validateRow($row);
                if ($validated['error']) {
                    $errors[$row['row']] = $validated['error'];
                    continue;
                }

                $data = $validated['data'];
                if ($data['sort_order'] === null) {
                    $nextSortOrder++;
                    $data['sort_order'] = $nextSortOrder;
                } else {
                    $nextSortOrder = max($nextSortOrder, $data['sort_order']);
                }

                QuizQuestion::create([
                    'quiz_id'        => $quizId,
                    'question'       => $data['question'],
                    'option_a'       => $data['option_a'],
                    'option_b'       => $data['option_b'],
                    'option_c'       => $data['option_c'],
                    'option_d'       => $data['option_d'],
                    'correct_answer' => $data['correct_answer'],
                    'points'         => $data['points'],
                    'sort_order'     => $data['sort_order'],
                    'time_limit'     => $data['time_limit'],
                    'status'         => $data['status'],
                    'created_by'     => $userId,
                ]);

                $imported++;
            }
        });

        return compact('imported', 'errors');
    }

    /**
     * @return list<array{row: int, question: string, option_a: string, option_b: string, option_c: string, option_d: string, correct_answer: string, points: string, sort_order: string, time_limit: string, status: string}>
     */
    private function parseRows(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getSheetByName('Template Soal') ?? $spreadsheet->getActiveSheet();

        $rows = [];
        $highestRow = $sheet->getHighestDataRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $question = trim((string) $sheet->getCell([1, $row])->getCalculatedValue());

            if ($question === '') {
                continue;
            }

            if (str_starts_with(strtolower($question), 'contoh:')) {
                continue;
            }

            $rows[] = [
                'row'            => $row,
                'question'       => $question,
                'option_a'       => trim((string) $sheet->getCell([2, $row])->getCalculatedValue()),
                'option_b'       => trim((string) $sheet->getCell([3, $row])->getCalculatedValue()),
                'option_c'       => trim((string) $sheet->getCell([4, $row])->getCalculatedValue()),
                'option_d'       => trim((string) $sheet->getCell([5, $row])->getCalculatedValue()),
                'correct_answer' => trim((string) $sheet->getCell([6, $row])->getCalculatedValue()),
                'points'         => trim((string) $sheet->getCell([7, $row])->getCalculatedValue()),
                'sort_order'     => trim((string) $sheet->getCell([8, $row])->getCalculatedValue()),
                'time_limit'     => trim((string) $sheet->getCell([9, $row])->getCalculatedValue()),
                'status'         => trim((string) $sheet->getCell([10, $row])->getCalculatedValue()),
            ];
        }

        return $rows;
    }

    /**
     * @param array{row: int, question: string, option_a: string, option_b: string, option_c: string, option_d: string, correct_answer: string, points: string, sort_order: string, time_limit: string, status: string} $row
     * @return array{error: ?string, data: ?array<string, mixed>}
     */
    private function validateRow(array $row): array
    {
        $prefix = "Baris {$row['row']}: ";

        foreach (['option_a', 'option_b', 'option_c', 'option_d'] as $field) {
            if ($row[$field] === '') {
                return ['error' => $prefix . 'Semua pilihan jawaban (A–D) wajib diisi.', 'data' => null];
            }
            if (strlen($row[$field]) > 255) {
                return ['error' => $prefix . 'Pilihan jawaban maksimal 255 karakter.', 'data' => null];
            }
        }

        $correct = strtolower($row['correct_answer']);
        if (! in_array($correct, ['a', 'b', 'c', 'd'], true)) {
            return ['error' => $prefix . 'Jawaban benar harus a, b, c, atau d.', 'data' => null];
        }

        $points = $row['points'] !== '' ? (int) $row['points'] : 1;
        if ($points < 1) {
            return ['error' => $prefix . 'Poin minimal 1.', 'data' => null];
        }

        $sortOrder = $row['sort_order'] !== '' ? (int) $row['sort_order'] : null;
        if ($sortOrder !== null && $sortOrder < 0) {
            return ['error' => $prefix . 'Urutan tidak boleh negatif.', 'data' => null];
        }

        $timeLimit = null;
        if ($row['time_limit'] !== '') {
            $timeLimit = (int) $row['time_limit'];
            if ($timeLimit < 5 || $timeLimit > 600) {
                return ['error' => $prefix . 'Waktu harus antara 5–600 detik.', 'data' => null];
            }
        }

        $status = strtolower($row['status'] !== '' ? $row['status'] : 'active');
        if (in_array($status, ['aktif'], true)) {
            $status = 'active';
        }
        if (! in_array($status, ['active', 'draft'], true)) {
            return ['error' => $prefix . 'Status harus active/aktif atau draft.', 'data' => null];
        }

        return [
            'error' => null,
            'data'  => [
                'question'       => $row['question'],
                'option_a'       => $row['option_a'],
                'option_b'       => $row['option_b'],
                'option_c'       => $row['option_c'],
                'option_d'       => $row['option_d'],
                'correct_answer' => $correct,
                'points'         => $points,
                'sort_order'     => $sortOrder,
                'time_limit'     => $timeLimit,
                'status'         => $status,
            ],
        ];
    }
}
