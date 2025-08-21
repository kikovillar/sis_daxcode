<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'system_name',
                'value' => 'Sistema de Avaliacao',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'system_description',
                'value' => 'Plataforma completa para criacao e gestao de avaliacoes educacionais',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'primary_color',
                'value' => '#3B82F6',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'secondary_color',
                'value' => '#1F2937',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'footer_text',
                'value' => 'Sistema de Avaliacao - Todos os direitos reservados',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'allow_registration',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'max_file_size',
                'value' => '2048',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'logo_path',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'favicon_path',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}