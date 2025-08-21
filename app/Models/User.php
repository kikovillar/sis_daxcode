<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'profile_photo',
        // Documentos
        'documento_rg',
        'documento_cnh',
        'documento_cpf',
        'documento_foto_3x4',
        // Dados Pessoais
        'data_nascimento',
        'idade',
        'rg',
        'cpf',
        'sexo',
        'telefone',
        'bf',
        'estado_civil',
        'tem_filhos',
        'quantidade_filhos',
        // Endereço
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        // Contato de Urgência
        'urgencia_nome_contato',
        'urgencia_telefone_contato',
        // Informações Físicas
        'tamanho_camisa',
        // Saúde
        'tem_deficiencia',
        'descricao_deficiencia',
        'tem_condicao_saude',
        'descricao_saude',
        'tem_alergia',
        'descricao_alergia',
        'usa_medicamento',
        'qual_medicamento',
        // Educação
        'ensino_pre_escolar',
        'ensino_fundamental_concluido',
        'ensino_fundamental_cursando',
        'ensino_fundamental_instituicao',
        'ensino_medio_concluido',
        'ensino_medio_cursando',
        'ensino_medio_instituicao',
        'ensino_tecnico_concluido',
        'ensino_tecnico_cursando',
        'ensino_tecnico_instituicao',
        'ensino_superior_concluido',
        'superior_cursando',
        'superior_trancado',
        'superior_instituicao',
        'pos_graduacao_concluido',
        'pos_graduacao_cursando',
        'pos_graduacao_instituicao',
        // Cursos Extras
        'curso_1',
        'curso_1_instituicao',
        'curso_2',
        'curso_2_instituicao',
        'curso_3',
        'curso_3_instituicao',
        // Profissional
        'situacao_profissional',
        'experiencia_1_instituicao',
        'experiencia_1_ano',
        'experiencia_1_funcao',
        'experiencia_1_atividades',
        'experiencia_2_instituicao',
        'experiencia_2_ano',
        'experiencia_2_funcao',
        'experiencia_2_atividades',
        // Habilidades
        'nivel_ingles',
        'desenvolveu_sistemas',
        'ja_empreendeu',
        // Disponibilidade
        'disponibilidade_dias',
        'disponibilidade_horario',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
            'tem_filhos' => 'boolean',
            'tem_deficiencia' => 'boolean',
            'tem_condicao_saude' => 'boolean',
            'tem_alergia' => 'boolean',
            'usa_medicamento' => 'boolean',
            'ensino_pre_escolar' => 'boolean',
            'ensino_fundamental_concluido' => 'boolean',
            'ensino_fundamental_cursando' => 'boolean',
            'ensino_medio_concluido' => 'boolean',
            'ensino_medio_cursando' => 'boolean',
            'ensino_tecnico_concluido' => 'boolean',
            'ensino_tecnico_cursando' => 'boolean',
            'ensino_superior_concluido' => 'boolean',
            'superior_cursando' => 'boolean',
            'superior_trancado' => 'boolean',
            'pos_graduacao_concluido' => 'boolean',
            'pos_graduacao_cursando' => 'boolean',
            'desenvolveu_sistemas' => 'boolean',
            'ja_empreendeu' => 'boolean',
            'disponibilidade_dias' => 'array',
        ];
    }

    // Relacionamentos

    /**
     * Turmas que o usuário ensina (se for professor)
     */
    public function teachingClasses()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }

    /**
     * Turmas que o usuário frequenta (se for aluno)
     */
    public function enrolledClasses()
    {
        return $this->belongsToMany(ClassModel::class, 'class_user', 'user_id', 'class_id')
                    ->withPivot('enrolled_at')
                    ->withTimestamps();
    }

    /**
     * Avaliações criadas pelo usuário (se for professor)
     */
    public function createdAssessments()
    {
        return $this->hasMany(Assessment::class, 'teacher_id');
    }

    /**
     * Tentativas de avaliação do usuário (se for aluno)
     */
    public function studentAssessments()
    {
        return $this->hasMany(StudentAssessment::class, 'student_id');
    }

    /**
     * Respostas corrigidas pelo usuário (se for professor)
     */
    public function gradedAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'graded_by');
    }

    /**
     * Questões criadas pelo usuário (se for professor)
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    // Scopes e métodos auxiliares

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return in_array($this->role, ['professor', 'teacher']);
    }

    public function isStudent(): bool
    {
        return $this->role === 'aluno';
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'professor');
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'aluno');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Verifica se o usuário pode acessar uma avaliação
     */
    public function canAccessAssessment(Assessment $assessment): bool
    {
        if ($this->isAdmin() || $assessment->teacher_id === $this->id) {
            return true;
        }

        if ($this->isStudent()) {
            return $this->enrolledClasses()
                        ->whereHas('assessments', function ($query) use ($assessment) {
                            $query->where('assessments.id', $assessment->id);
                        })
                        ->exists();
        }

        return false;
    }

    /**
     * Obtém a tentativa ativa de uma avaliação
     */
    public function getActiveAssessmentAttempt(Assessment $assessment): ?StudentAssessment
    {
        return $this->studentAssessments()
                   ->where('assessment_id', $assessment->id)
                   ->where('status', 'in_progress')
                   ->first();
    }

    /**
     * Verificar se o usuário está ativo
     */
    public function isActive()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Verificar se o usuário está inativo
     */
    public function isInactive()
    {
        return is_null($this->email_verified_at);
    }

    /**
     * Ativar o usuário
     */
    public function activate()
    {
        $this->update(['email_verified_at' => now()]);
    }

    /**
     * Desativar o usuário
     */
    public function deactivate()
    {
        $this->update(['email_verified_at' => null]);
    }

    /**
     * Obter URL da foto de perfil
     */
    public function getProfilePhotoUrl()
    {
        if ($this->profile_photo && !empty($this->profile_photo)) {
            // Verificar se o arquivo existe
            if (\Storage::disk('public')->exists($this->profile_photo)) {
                return asset('storage/' . $this->profile_photo);
            }
        }
        
        // Retornar avatar padrão baseado na inicial do nome
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Obter URL do avatar padrão
     */
    public function getDefaultAvatarUrl()
    {
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=6366f1&color=ffffff&size=200";
    }

    /**
     * Verificar se tem foto de perfil
     */
    public function hasProfilePhoto()
    {
        return !is_null($this->profile_photo);
    }

    /**
     * Remover foto de perfil
     */
    public function removeProfilePhoto()
    {
        if ($this->profile_photo && !empty($this->profile_photo)) {
            if (\Storage::disk('public')->exists($this->profile_photo)) {
                \Storage::disk('public')->delete($this->profile_photo);
            }
            $this->update(['profile_photo' => null]);
        }
    }
}