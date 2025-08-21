<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar questões que não têm created_by definido
        $firstTeacher = DB::table('users')
            ->where('role', 'professor')
            ->orWhere('role', 'admin')
            ->first();
        
        if ($firstTeacher) {
            DB::table('questions')
                ->whereNull('created_by')
                ->update(['created_by' => $firstTeacher->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não reverter para não perder dados
    }
};