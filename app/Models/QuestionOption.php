<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'content',
        'is_correct',
        'order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'order' => 'integer',
    ];

    // Relacionamentos

    /**
     * Questão à qual a opção pertence
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Respostas dos alunos que selecionaram esta opção
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'selected_option_id');
    }

    // Métodos auxiliares

    /**
     * Obtém estatísticas da opção
     */
    public function getStats(): array
    {
        $totalSelections = $this->studentAnswers()->count();
        $totalQuestionAnswers = $this->question->studentAnswers()->count();

        return [
            'total_selections' => $totalSelections,
            'selection_rate' => $totalQuestionAnswers > 0 ? ($totalSelections / $totalQuestionAnswers) * 100 : 0,
            'is_correct' => $this->is_correct,
        ];
    }
}