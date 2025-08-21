<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InitializeSystemSettings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:init-settings';

    /**
     * The console command description.
     */
    protected $description = 'Initialize system settings table with default values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inicializando configurações do sistema...');

        // Verificar se a tabela existe
        if (!Schema::hasTable('system_settings')) {
            $this->error('Tabela system_settings não existe. Execute: php artisan migrate');
            return 1;
        }

        // Configurações padrão
        $defaultSettings = [
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

        $inserted = 0;
        foreach ($defaultSettings as $setting) {
            // Verificar se a configuração já existe
            $exists = DB::table('system_settings')
                ->where('key', $setting['key'])
                ->exists();

            if (!$exists) {
                DB::table('system_settings')->insert($setting);
                $inserted++;
                $this->line("✅ Configuração '{$setting['key']}' adicionada");
            } else {
                $this->line("⏭️  Configuração '{$setting['key']}' já existe");
            }
        }

        if ($inserted > 0) {
            $this->info("✅ {$inserted} configuração(ões) inicializada(s) com sucesso!");
        } else {
            $this->info("ℹ️  Todas as configurações já estavam inicializadas.");
        }

        // Limpar cache
        \Illuminate\Support\Facades\Cache::forget('system_settings');
        $this->info("🔄 Cache de configurações limpo.");

        return 0;
    }
}