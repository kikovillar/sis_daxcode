<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
    ];

    // Relacionamentos

    /**
     * Professor responsável pela turma
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Alunos matriculados na turma
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
                    ->withPivot('enrolled_at')
                    ->withTimestamps()
                    ->using(ClassUser::class);
    }

    /**
     * Avaliações da turma
     */
    public function assessments()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_class', 'class_id', 'assessment_id');
    }

    // Métodos auxiliares

    /**
     * Verifica se um usuário está matriculado na turma
     */
    public function hasStudent(User $user): bool
    {
        return $this->students()->where('users.id', $user->id)->exists();
    }

    /**
     * Matricula um aluno na turma
     */
    public function enrollStudent(User $student): void
    {
        if (!$this->hasStudent($student)) {
            $this->students()->attach($student->id, [
                'enrolled_at' => now(),
            ]);
        }
    }

    /**
     * Remove um aluno da turma
     */
    public function unenrollStudent(User $student): void
    {
        $this->students()->detach($student->id);
    }

    /**
     * Obtém estatísticas da turma
     */
    public function getStats(): array
    {
        $totalStudents = $this->students()->count();
        $totalAssessments = $this->assessments()->count();
        $publishedAssessments = $this->assessments()->where('status', 'published')->count();

        return [
            'total_students' => $totalStudents,
            'total_assessments' => $totalAssessments,
            'published_assessments' => $publishedAssessments,
        ];
    }
}