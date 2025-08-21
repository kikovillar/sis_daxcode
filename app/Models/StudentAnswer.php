<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_assessment_id',
        'question_id',
        'answer_text',
        'selected_option_id',
        'is_correct',
        'points_earned',
        'graded_at',
        'graded_by',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'decimal:2',
        'graded_at' => 'datetime',
    ];

    // Relacionamentos

    /**
     * Tentativa de avaliação
     */
    public function studentAssessment()
    {
        return $this->belongsTo(StudentAssessment::class);
    }

    /**
     * Questão respondida
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Opção selecionada (para questões objetivas)
     */
    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }

    /**
     * Professor que corrigiu (para questões dissertativas)
     */
    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // Scopes

    public function scopeGraded($query)
    {
        return $query->whereNotNull('graded_at');
    }

    public function scopeUngraded($query)
    {
        return $query->whereNull('graded_at');
    }

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    public function scopeEssayAnswers($query)
    {
        return $query->whereHas('question', function ($q) {
            $q->where('type', 'essay');
        });
    }

    public function scopeObjectiveAnswers($query)
    {
        return $query->whereHas('question', function ($q) {
            $q->whereIn('type', ['multiple_choice', 'true_false']);
        });
    }

    // Métodos auxiliares

    /**
     * Verifica se a resposta foi corrigida
     */
    public function isGraded(): bool
    {
        return !is_null($this->graded_at);
    }

    /**
     * Verifica se precisa de correção manual
     */
    public function needsManualGrading(): bool
    {
        return $this->question->isEssay() && !$this->isGraded();
    }

    /**
     * Corrige a resposta manualmente
     */
    public function grade(float $points, User $grader, bool $isCorrect = null): void
    {
        $this->update([
            'points_earned' => $points,
            'is_correct' => $isCorrect,
            'graded_at' => now(),
            'graded_by' => $grader->id,
        ]);

        // Atualiza a pontuação total da tentativa
        $this->studentAssessment->update([
            'score' => $this->studentAssessment->calculateScore(),
        ]);
    }

    /**
     * Obtém o texto da resposta formatado
     */
    public function getFormattedAnswer(): string
    {
        if ($this->question->isObjective() && $this->selectedOption) {
            return $this->selectedOption->content;
        }

        return $this->answer_text ?? '';
    }

    /**
     * Verifica se a resposta está correta
     */
    public function isCorrect(): bool
    {
        return $this->is_correct === true;
    }

    /**
     * Obtém a pontuação máxima possível para esta resposta
     */
    public function getMaxPoints(): float
    {
        $assessmentQuestion = $this->studentAssessment->assessment
            ->questions()
            ->where('questions.id', $this->question_id)
            ->first();

        return $assessmentQuestion->pivot->points_override ?? $this->question->points;
    }

    /**
     * Calcula a porcentagem de acerto
     */
    public function getScorePercentage(): float
    {
        $maxPoints = $this->getMaxPoints();
        
        if ($maxPoints <= 0) {
            return 0;
        }

        return ($this->points_earned / $maxPoints) * 100;
    }
}