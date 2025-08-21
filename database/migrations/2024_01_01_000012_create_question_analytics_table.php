<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->integer('total_attempts')->default(0);
            $table->integer('correct_attempts')->default(0);
            $table->decimal('average_time_seconds', 8, 2)->default(0);
            $table->timestamp('updated_at');

            $table->unique(['question_id', 'assessment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_analytics');
    }
};