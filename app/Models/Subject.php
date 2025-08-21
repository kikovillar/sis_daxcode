<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    // Relacionamentos

    /**
     * Avaliações da disciplina
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    /**
     * Questões da disciplina
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Usuário que criou a disciplina
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Métodos auxiliares

    /**
     * Obtém estatísticas da disciplina
     */
    public function getStats(): array
    {
        return [
            'total_questions' => $this->questions()->count(),
            'total_assessments' => $this->assessments()->count(),
            'questions_by_difficulty' => $this->questions()
                ->selectRaw('difficulty, COUNT(*) as count')
                ->groupBy('difficulty')
                ->pluck('count', 'difficulty')
                ->toArray(),
        ];
    }
}