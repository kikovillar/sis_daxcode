<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnalytic extends Model
{
    use HasFactory;

    // Desabilitar timestamps automáticos já que a tabela não tem created_at
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'assessment_id',
        'total_attempts',
        'correct_attempts',
        'average_time_seconds',
    ];

    protected $casts = [
        'total_attempts' => 'integer',
        'correct_attempts' => 'integer',
        'average_time_seconds' => 'decimal:2',
    ];

    // Relacionamentos

    /**
     * Questão analisada
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Avaliação analisada
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Métodos auxiliares

    /**
     * Calcula a taxa de acerto
     */
    public function getAccuracyRate(): float
    {
        if ($this->total_attempts === 0) {
            return 0;
        }

        return ($this->correct_attempts / $this->total_attempts) * 100;
    }

    /**
     * Atualiza as estatísticas
     */
    public function updateStats(): void
    {
        $answers = StudentAnswer::whereHas('studentAssessment', function ($query) {
                return $query->where('assessment_id', $this->assessment_id);
            })
            ->where('question_id', $this->question_id)
            ->whereHas('studentAssessment', function ($query) {
                return $query->where('status', 'completed');
            });

        $this->update([
            'total_attempts' => $answers->count(),
            'correct_attempts' => $answers->where('is_correct', true)->count(),
            'average_time_seconds' => $this->calculateAverageTime(),
        ]);
    }

    /**
     * Calcula o tempo médio gasto na questão
     */
    private function calculateAverageTime(): float
    {
        // Esta implementação seria mais complexa na prática,
        // precisaria rastrear o tempo gasto em cada questão
        // Por enquanto, retorna um valor estimado
        return 120; // 2 minutos como exemplo
    }

    /**
     * Obtém estatísticas detalhadas
     */
    public function getDetailedStats(): array
    {
        return [
            'total_attempts' => $this->total_attempts,
            'correct_attempts' => $this->correct_attempts,
            'incorrect_attempts' => $this->total_attempts - $this->correct_attempts,
            'accuracy_rate' => $this->getAccuracyRate(),
            'average_time_seconds' => $this->average_time_seconds,
            'average_time_formatted' => gmdate('i:s', $this->average_time_seconds),
            'difficulty_assessment' => $this->assessDifficulty(),
        ];
    }

    /**
     * Avalia a dificuldade baseada na taxa de acerto
     */
    private function assessDifficulty(): string
    {
        $accuracy = $this->getAccuracyRate();

        if ($accuracy >= 80) {
            return 'Fácil';
        } elseif ($accuracy >= 60) {
            return 'Médio';
        } elseif ($accuracy >= 40) {
            return 'Difícil';
        } else {
            return 'Muito Difícil';
        }
    }
}