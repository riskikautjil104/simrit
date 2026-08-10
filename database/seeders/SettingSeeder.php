<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'site_name',        'value' => 'SIMRIT Chasan Boesoirie',                            'type' => 'string'],
            ['key' => 'site_tagline',     'value' => 'Sistem Informasi Manajemen Ruang IT',                'type' => 'string'],
            ['key' => 'site_description', 'value' => 'Portal informasi resmi Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate', 'type' => 'text'],
            ['key' => 'address',          'value' => 'RSUD Dr. H. Chasan Boesoirie, Ternate, Maluku Utara', 'type' => 'text'],
            ['key' => 'phone',            'value' => '',                                                    'type' => 'string'],
            ['key' => 'email',            'value' => '',                                                    'type' => 'string'],
            ['key' => 'logo',             'value' => '',                                                    'type' => 'image'],
            ['key' => 'favicon',          'value' => '',                                                    'type' => 'image'],
            ['key' => 'footer_text',      'value' => '© ' . date('Y') . ' Ruang IT RSUD Dr. H. Chasan Boesoirie Ternate', 'type' => 'string'],
            ['key' => 'items_per_page',   'value' => '12',                                                  'type' => 'string'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
