<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Inserir configurações padrão
        DB::table('system_settings')->insert([
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
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};