<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'max_score',
        'opens_at',
        'closes_at',
        'status',
        'subject_id',
        'teacher_id',
        'settings',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'duration_minutes' => 'integer',
        'max_score' => 'decimal:2',
        'settings' => 'array',
    ];

    // Relacionamentos

    /**
     * Disciplina da avaliação
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Professor que criou a avaliação
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Turmas que podem fazer a avaliação
     */
    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'assessment_class', 'assessment_id', 'class_id');
    }

    /**
     * Questões da avaliação
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'assessment_questions')
                    ->withPivot('order', 'points_override')
                    ->orderBy('assessment_questions.order');
    }

    /**
     * Tentativas dos alunos
     */
    public function studentAssessments()
    {
        return $this->hasMany(StudentAssessment::class);
    }

    /**
     * Alunos que podem fazer a avaliação
     */
    public function eligibleStudents()
    {
        return User::whereHas('enrolledClasses', function ($query) {
            $query->whereIn('classes.id', $this->classes()->pluck('classes.id'));
        })->where('role', 'aluno');
    }

    // Scopes

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'published')
                    ->where('opens_at', '<=', now())
                    ->where('closes_at', '>=', now());
    }

    // Métodos auxiliares

    /**
     * Verifica se a avaliação está disponível para ser feita
     */
    public function isAvailable(): bool
    {
        return $this->status === 'published' 
            && $this->opens_at <= now() 
            && $this->closes_at >= now();
    }

    /**
     * Verifica se a avaliação já foi aberta
     */
    public function hasOpened(): bool
    {
        return $this->opens_at <= now();
    }

    /**
     * Verifica se a avaliação já foi fechada
     */
    public function hasClosed(): bool
    {
        return $this->closes_at < now() || $this->status === 'closed';
    }

    /**
     * Calcula a pontuação total da avaliação
     */
    public function getTotalPoints(): float
    {
        return $this->questions()->sum(function ($question) {
            return $question->pivot->points_override ?? $question->points;
        });
    }

    /**
     * Verifica se um aluno pode fazer a avaliação
     */
    public function canBeAttemptedBy(User $student): bool
    {
        if (!$student->isStudent() || !$this->isAvailable()) {
            return false;
        }

        // Verifica se o aluno está em alguma turma da avaliação
        return $student->enrolledClasses()
                      ->whereIn('classes.id', $this->classes()->pluck('classes.id'))
                      ->exists();
    }

    /**
     * Obtém a tentativa ativa de um aluno
     */
    public function getActiveAttemptFor(User $student): ?StudentAssessment
    {
        return $this->studentAssessments()
                   ->where('student_id', $student->id)
                   ->where('status', 'in_progress')
                   ->first();
    }

    /**
     * Verifica se um aluno já completou a avaliação
     */
    public function isCompletedBy(User $student): bool
    {
        return $this->studentAssessments()
                   ->where('student_id', $student->id)
                   ->where('status', 'completed')
                   ->exists();
    }

    /**
     * Obtém estatísticas da avaliação
     */
    public function getStats(): array
    {
        $attempts = $this->studentAssessments();
        
        return [
            'total' => $attempts->count(),
            'completed' => $attempts->where('status', 'completed')->count(),
            'in_progress' => $attempts->where('status', 'in_progress')->count(),
            'average_score' => $attempts->where('status', 'completed')->avg('score') ?? 0,
            'highest_score' => $attempts->where('status', 'completed')->max('score') ?? 0,
            'lowest_score' => $attempts->where('status', 'completed')->min('score') ?? 0,
        ];
    }

    /**
     * Publica a avaliação
     */
    public function publish(): void
    {
        $this->update(['status' => 'published']);
    }

    /**
     * Fecha a avaliação
     */
    public function close(): void
    {
        $this->update(['status' => 'closed']);
        
        // Finaliza tentativas em andamento
        $this->studentAssessments()
             ->where('status', 'in_progress')
             ->update([
                 'status' => 'expired',
                 'finished_at' => now(),
             ]);
    }
}