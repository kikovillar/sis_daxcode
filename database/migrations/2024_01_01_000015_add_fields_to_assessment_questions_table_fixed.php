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
        Schema::table('assessment_questions', function (Blueprint $table) {
            // Verificar se as colunas já existem antes de adicionar
            if (!Schema::hasColumn('assessment_questions', 'is_required')) {
                $table->boolean('is_required')->default(true)->after('points_override');
            }
            if (!Schema::hasColumn('assessment_questions', 'show_in_random')) {
                $table->boolean('show_in_random')->default(true)->after('is_required');
            }
            if (!Schema::hasColumn('assessment_questions', 'custom_instructions')) {
                $table->text('custom_instructions')->nullable()->after('show_in_random');
            }
            // Não adicionar timestamps se já existirem
            if (!Schema::hasColumn('assessment_questions', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            $columns = ['is_required', 'show_in_random', 'custom_instructions'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('assessment_questions', $column)) {
                    $table->dropColumn($column);
                }
            }
            // Não remover created_at e updated_at se já existiam antes desta migração
        });
    }
};