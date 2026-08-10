<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_key' => 'history',
                'title'    => 'Sejarah Ruang IT',
                'slug'     => 'sejarah',
                'excerpt'  => 'Sejarah perjalanan Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate.',
                'content'  => '<p>Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate didirikan sebagai unit yang bertanggung jawab atas pengelolaan dan pengembangan sistem informasi di lingkungan rumah sakit.</p><p>Sejak berdirinya, Ruang IT terus berkembang dan berinovasi untuk mendukung pelayanan kesehatan yang lebih baik kepada masyarakat Maluku Utara.</p>',
                'status'   => 'published',
            ],
            [
                'page_key' => 'vision-mission',
                'title'    => 'Visi & Misi',
                'slug'     => 'visi-misi',
                'excerpt'  => 'Visi dan misi Ruang IT dalam mendukung pelayanan kesehatan digital.',
                'content'  => '<h2>Visi</h2><p>Menjadi unit IT yang profesional, inovatif, dan terpercaya dalam mendukung transformasi digital layanan kesehatan RSUD Dr. H. Chasan Boesoirie Ternate.</p><h2>Misi</h2><ul><li>Mengelola infrastruktur teknologi informasi yang handal dan aman.</li><li>Mengembangkan sistem informasi yang mendukung efisiensi operasional rumah sakit.</li><li>Memberikan layanan dukungan teknis yang responsif kepada seluruh unit kerja.</li><li>Meningkatkan kompetensi SDM IT secara berkelanjutan.</li></ul>',
                'status'   => 'published',
            ],
            [
                'page_key' => 'organization',
                'title'    => 'Struktur Organisasi',
                'slug'     => 'struktur-organisasi',
                'excerpt'  => 'Susunan organisasi Ruang IT RSUD Dr. H. Chasan Boesoirie.',
                'content'  => '<p>Struktur organisasi Ruang IT disusun untuk memastikan pengelolaan teknologi informasi yang terstruktur dan efektif dalam mendukung pelayanan rumah sakit.</p>',
                'status'   => 'published',
            ],
            [
                'page_key' => 'duties-functions',
                'title'    => 'Tugas & Fungsi',
                'slug'     => 'tugas-fungsi',
                'excerpt'  => 'Tugas pokok dan fungsi Ruang IT dalam ekosistem RSUD Dr. H. Chasan Boesoirie.',
                'content'  => '<h2>Tugas Pokok</h2><p>Ruang IT bertugas merencanakan, mengembangkan, mengoperasikan, dan memelihara infrastruktur teknologi informasi dan komunikasi di lingkungan RSUD Dr. H. Chasan Boesoirie Ternate.</p><h2>Fungsi</h2><ul><li>Pengelolaan infrastruktur jaringan dan server.</li><li>Pengembangan dan pemeliharaan aplikasi/sistem informasi.</li><li>Pengelolaan keamanan informasi.</li><li>Dukungan teknis kepada pengguna.</li><li>Perencanaan dan pengadaan perangkat IT.</li></ul>',
                'status'   => 'published',
            ],
            [
                'page_key' => 'facilities',
                'title'    => 'Sarana & Prasarana',
                'slug'     => 'sarana-prasarana',
                'excerpt'  => 'Sarana dan prasarana IT yang dimiliki oleh RSUD Dr. H. Chasan Boesoirie.',
                'content'  => '<p>Ruang IT RSUD Dr. H. Chasan Boesoirie dilengkapi dengan berbagai sarana dan prasarana untuk mendukung operasional teknologi informasi rumah sakit, termasuk server room, perangkat jaringan, dan workstation untuk tim IT.</p>',
                'status'   => 'published',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['page_key' => $page['page_key']],
                array_merge($page, ['published_at' => now()])
            );
        }
    }
}
