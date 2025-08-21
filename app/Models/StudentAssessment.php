<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'student_id',
        'started_at',
        'finished_at',
        'score',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    // Relacionamentos

    /**
     * Avaliação sendo realizada
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Aluno realizando a avaliação
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Respostas do aluno
     */
    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    // Scopes

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    // Métodos auxiliares

    /**
     * Verifica se a tentativa está em andamento
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Verifica se a tentativa foi completada
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verifica se a tentativa expirou
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Calcula o tempo restante em segundos
     */
    public function getRemainingTimeInSeconds(): int
    {
        if (!$this->isInProgress()) {
            return 0;
        }

        $endTime = $this->started_at->addMinutes($this->assessment->duration_minutes);
        $now = now();

        if ($now >= $endTime) {
            return 0;
        }

        return $now->diffInSeconds($endTime, false);
    }

    /**
     * Verifica se o tempo expirou
     */
    public function hasTimeExpired(): bool
    {
        return $this->getRemainingTimeInSeconds() <= 0;
    }

    /**
     * Calcula o tempo gasto até agora
     */
    public function getElapsedTimeInSeconds(): int
    {
        if (!$this->started_at) {
            return 0;
        }

        $endTime = $this->finished_at ?? now();
        return $this->started_at->diffInSeconds($endTime);
    }

    /**
     * Obtém o progresso da avaliação (0-100)
     */
    public function getProgress(): float
    {
        $totalQuestions = $this->assessment->questions()->count();
        $answeredQuestions = $this->answers()->count();

        if ($totalQuestions === 0) {
            return 100;
        }

        return ($answeredQuestions / $totalQuestions) * 100;
    }

    /**
     * Verifica se todas as questões foram respondidas
     */
    public function isFullyAnswered(): bool
    {
        return $this->getProgress() >= 100;
    }

    /**
     * Finaliza a tentativa
     */
    public function finish(): void
    {
        $this->update([
            'finished_at' => now(),
            'status' => 'completed',
            'score' => $this->calculateScore(),
        ]);
    }

    /**
     * Expira a tentativa por tempo
     */
    public function expire(): void
    {
        $this->update([
            'finished_at' => now(),
            'status' => 'expired',
            'score' => $this->calculateScore(),
        ]);
    }

    /**
     * Calcula a pontuação total
     */
    public function calculateScore(): float
    {
        return $this->answers()->sum('points_earned');
    }

    /**
     * Obtém a próxima questão não respondida
     */
    public function getNextUnansweredQuestion(): ?Question
    {
        $answeredQuestionIds = $this->answers()->pluck('question_id')->toArray();
        
        return $this->assessment->questions()
                   ->whereNotIn('questions.id', $answeredQuestionIds)
                   ->orderBy('assessment_questions.order')
                   ->first();
    }

    /**
     * Obtém uma questão específica da avaliação
     */
    public function getQuestion(int $questionId): ?Question
    {
        return $this->assessment->questions()
                   ->where('questions.id', $questionId)
                   ->first();
    }

    /**
     * Obtém a resposta para uma questão específica
     */
    public function getAnswerForQuestion(int $questionId): ?StudentAnswer
    {
        return $this->answers()
                   ->where('question_id', $questionId)
                   ->first();
    }

    /**
     * Salva ou atualiza uma resposta
     */
    public function saveAnswer(int $questionId, $answerData): StudentAnswer
    {
        $question = $this->getQuestion($questionId);
        
        if (!$question) {
            throw new \Exception('Questão não encontrada na avaliação');
        }

        $answer = $this->answers()->updateOrCreate(
            ['question_id' => $questionId],
            [
                'answer_text' => $answerData['text'] ?? null,
                'selected_option_id' => $answerData['option_id'] ?? null,
            ]
        );

        // Calcula pontuação para questões objetivas
        if ($question->isObjective()) {
            $pointsOverride = $question->pivot->points_override;
            $isCorrect = $question->isAnswerCorrect($answerData['option_id'] ?? $answerData['text']);
            
            $answer->update([
                'is_correct' => $isCorrect,
                'points_earned' => $question->calculateScore($answerData['option_id'] ?? $answerData['text'], $pointsOverride),
            ]);
        }

        return $answer;
    }

    /**
     * Obtém estatísticas da tentativa
     */
    public function getStats(): array
    {
        $totalQuestions = $this->assessment->questions()->count();
        $answeredQuestions = $this->answers()->count();
        $correctAnswers = $this->answers()->where('is_correct', true)->count();
        $maxScore = $this->assessment->getTotalPoints();

        return [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'correct_answers' => $correctAnswers,
            'accuracy_rate' => $answeredQuestions > 0 ? ($correctAnswers / $answeredQuestions) * 100 : 0,
            'score' => $this->score ?? 0,
            'max_score' => $maxScore,
            'score_percentage' => $maxScore > 0 ? (($this->score ?? 0) / $maxScore) * 100 : 0,
            'time_elapsed' => $this->getElapsedTimeInSeconds(),
            'progress' => $this->getProgress(),
        ];
    }
}