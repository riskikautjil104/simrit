<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizRegistration;
use App\Services\QuizQuestionExcelService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuizExportController extends Controller
{
    public function downloadQuestionTemplate(QuizQuestionExcelService $service): StreamedResponse
    {
        return $service->downloadTemplate();
    }

    /**
     * Export participants as a CSV attendance sheet.
     * If quiz_id is provided, export only participants for that quiz.
     * Otherwise, export all approved participants.
     */
    public function exportAttendance(Request $request): StreamedResponse
    {
        $quizId = $request->query('quiz_id');
        $status = $request->query('status', '');

        $query = QuizRegistration::with('user', 'quiz')->latest();

        if ($quizId) {
            $query->where('quiz_id', $quizId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $registrations = $query->get();

        // Build filename
        $quizName = $quizId ? (Quiz::find($quizId)?->name ?? 'Kuis') : 'Semua-Peserta';
        $quizName = preg_replace('/[^A-Za-z0-9\-_]/', '-', $quizName);
        $filename = "Daftar-Hadir_{$quizName}_" . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($registrations) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM so Excel opens the file correctly
            fputs($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'No.',
                'Nama Lengkap',
                'Jabatan / Instansi',
                'Email',
                'No. WhatsApp',
                'Status Pendaftaran',
                'Kuis / Sesi',
                'Terdaftar Pada',
                'Status Akun',
                'Selesai Ujian',
                'Tanda Tangan / Paraf',
                'Keterangan Hadir',
            ]);

            foreach ($registrations as $index => $reg) {
                $quizName  = $reg->quiz?->name ?? '-';
                $accStatus = $reg->user ? ($reg->user->is_active ? 'Aktif' : 'Non-aktif') : 'Belum Ada Akun';
                $finished  = $reg->finished_at ? $reg->finished_at->format('d/m/Y H:i') : 'Belum Selesai';

                fputcsv($handle, [
                    $index + 1,
                    $reg->name,
                    $reg->position,
                    $reg->email,
                    $reg->phone,
                    ucfirst($reg->status),
                    $quizName,
                    $reg->created_at->format('d/m/Y H:i'),
                    $accStatus,
                    $finished,
                    '',   // Kolom tanda tangan - dikosongkan untuk diisi manual
                    '',   // Kolom keterangan hadir - dikosongkan untuk diisi manual
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
