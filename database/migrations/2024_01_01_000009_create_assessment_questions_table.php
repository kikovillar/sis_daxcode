<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('order')->default(1);
            $table->decimal('points_override', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_questions');
    }
};