<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'SOP',
            'Kebijakan',
            'Panduan',
            'SK (Surat Keputusan)',
            'Laporan',
            'Formulir',
            'Materi Pelatihan',
            'Dokumen Lainnya',
        ];

        foreach ($categories as $name) {
            DocumentCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name)]
            );
        }
    }
}
