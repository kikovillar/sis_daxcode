<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'difficulty',
        'points',
        'subject_id',
        'explanation',
        'created_by',
        'image_path',
        'image_description',
    ];

    protected $casts = [
        'points' => 'decimal:2',
    ];

    // Relacionamentos

    /**
     * Disciplina da questão
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Opções da questão (para múltipla escolha e V/F)
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    /**
     * Avaliações que usam esta questão
     */
    public function assessments()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_questions')
                    ->withPivot('order', 'points_override');
    }

    /**
     * Respostas dos alunos para esta questão
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    /**
     * Analytics da questão
     */
    public function analytics()
    {
        return $this->hasMany(QuestionAnalytic::class);
    }

    /**
     * Usuário que criou a questão
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    public function scopeMultipleChoice($query)
    {
        return $query->where('type', 'multiple_choice');
    }

    public function scopeTrueFalse($query)
    {
        return $query->where('type', 'true_false');
    }

    public function scopeEssay($query)
    {
        return $query->where('type', 'essay');
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    // Métodos auxiliares

    /**
     * Verifica se é questão objetiva
     */
    public function isObjective(): bool
    {
        return in_array($this->type, ['multiple_choice', 'true_false']);
    }

    /**
     * Verifica se é questão dissertativa
     */
    public function isEssay(): bool
    {
        return $this->type === 'essay';
    }

    /**
     * Obtém a opção correta (para questões objetivas)
     */
    public function getCorrectOption(): ?QuestionOption
    {
        return $this->options()->where('is_correct', true)->first();
    }

    /**
     * Verifica se uma resposta está correta
     */
    public function isAnswerCorrect($answer): bool
    {
        if ($this->isEssay()) {
            return false; // Questões dissertativas precisam ser corrigidas manualmente
        }

        $correctOption = $this->getCorrectOption();
        
        if (!$correctOption) {
            return false;
        }

        // Para múltipla escolha, compara o ID da opção
        if ($this->type === 'multiple_choice') {
            return (int) $answer === $correctOption->id;
        }

        // Para verdadeiro/falso, compara o conteúdo
        if ($this->type === 'true_false') {
            return strtolower(trim($answer)) === strtolower(trim($correctOption->content));
        }

        return false;
    }

    /**
     * Calcula a pontuação para uma resposta
     */
    public function calculateScore($answer, ?float $pointsOverride = null): float
    {
        $maxPoints = $pointsOverride ?? $this->points;

        if ($this->isEssay()) {
            return 0; // Será definido na correção manual
        }

        return $this->isAnswerCorrect($answer) ? $maxPoints : 0;
    }

    /**
     * Obtém estatísticas da questão
     */
    public function getStats(): array
    {
        $totalAnswers = $this->studentAnswers()->count();
        $correctAnswers = $this->studentAnswers()->where('is_correct', true)->count();

        return [
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'accuracy_rate' => $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0,
            'difficulty_level' => $this->difficulty,
            'average_points' => $this->studentAnswers()->avg('points_earned') ?? 0,
        ];
    }

    /**
     * Cria opções para questão de múltipla escolha
     */
    public function createOptions(array $options): void
    {
        foreach ($options as $index => $option) {
            $this->options()->create([
                'content' => $option['content'],
                'is_correct' => $option['is_correct'] ?? false,
                'order' => $index + 1,
            ]);
        }
    }

    /**
     * Cria opções para questão verdadeiro/falso
     */
    public function createTrueFalseOptions(bool $correctAnswer = true): void
    {
        $this->options()->createMany([
            [
                'content' => 'Verdadeiro',
                'is_correct' => $correctAnswer,
                'order' => 1,
            ],
            [
                'content' => 'Falso',
                'is_correct' => !$correctAnswer,
                'order' => 2,
            ],
        ]);
    }
}