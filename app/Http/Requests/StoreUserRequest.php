<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,professor,aluno',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            
            // Documentos
            'documento_rg' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documento_cnh' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documento_cpf' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documento_foto_3x4' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            
            // Dados Pessoais
            'data_nascimento' => 'nullable|date|before:today',
            'idade' => 'nullable|integer|min:1|max:120',
            'rg' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'sexo' => 'nullable|in:masculino,feminino,outro',
            'telefone' => 'nullable|string|max:20',
            'bf' => 'nullable|string|max:255',
            'estado_civil' => 'nullable|in:solteiro,casado,divorciado,viuvo,uniao_estavel',
            'tem_filhos' => 'nullable|boolean',
            'quantidade_filhos' => 'nullable|integer|min:0|max:20',
            
            // Endereço
            'endereco' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            
            // Contato de Urgência
            'urgencia_nome_contato' => 'nullable|string|max:255',
            'urgencia_telefone_contato' => 'nullable|string|max:20',
            
            // Informações Físicas
            'tamanho_camisa' => 'nullable|in:PP,P,M,G,GG,XG,XXG',
            
            // Saúde
            'tem_deficiencia' => 'nullable|boolean',
            'descricao_deficiencia' => 'nullable|string|max:1000',
            'tem_condicao_saude' => 'nullable|boolean',
            'descricao_saude' => 'nullable|string|max:1000',
            'tem_alergia' => 'nullable|boolean',
            'descricao_alergia' => 'nullable|string|max:1000',
            'usa_medicamento' => 'nullable|boolean',
            'qual_medicamento' => 'nullable|string|max:1000',
            
            // Educação
            'ensino_pre_escolar' => 'nullable|boolean',
            'ensino_fundamental_status' => 'nullable|in:nao,cursando,concluido',
            'ensino_fundamental_instituicao' => 'nullable|string|max:255',
            'ensino_medio_status' => 'nullable|in:nao,cursando,concluido',
            'ensino_medio_instituicao' => 'nullable|string|max:255',
            'ensino_tecnico_status' => 'nullable|in:nao,cursando,concluido',
            'ensino_tecnico_instituicao' => 'nullable|string|max:255',
            'ensino_superior_status' => 'nullable|in:nao,cursando,trancado,concluido',
            'superior_instituicao' => 'nullable|string|max:255',
            'pos_graduacao_status' => 'nullable|in:nao,cursando,concluido',
            'pos_graduacao_instituicao' => 'nullable|string|max:255',
            
            // Cursos Extras
            'curso_1' => 'nullable|string|max:255',
            'curso_1_instituicao' => 'nullable|string|max:255',
            'curso_2' => 'nullable|string|max:255',
            'curso_2_instituicao' => 'nullable|string|max:255',
            'curso_3' => 'nullable|string|max:255',
            'curso_3_instituicao' => 'nullable|string|max:255',
            
            // Profissional
            'situacao_profissional' => 'nullable|in:empregado,desempregado,autonomo,estudante,aposentado',
            'experiencia_1_instituicao' => 'nullable|string|max:255',
            'experiencia_1_ano' => 'nullable|string|max:10',
            'experiencia_1_funcao' => 'nullable|string|max:255',
            'experiencia_1_atividades' => 'nullable|string|max:1000',
            'experiencia_2_instituicao' => 'nullable|string|max:255',
            'experiencia_2_ano' => 'nullable|string|max:10',
            'experiencia_2_funcao' => 'nullable|string|max:255',
            'experiencia_2_atividades' => 'nullable|string|max:1000',
            
            // Habilidades
            'nivel_ingles' => 'nullable|in:basico,avancado,fluente',
            'desenvolveu_sistemas' => 'nullable|boolean',
            'ja_empreendeu' => 'nullable|boolean',
            
            // Disponibilidade
            'disponibilidade_dias' => 'nullable|array',
            'disponibilidade_dias.*' => 'string|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'disponibilidade_horario' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'role.required' => 'O tipo de usuário é obrigatório.',
            'role.in' => 'Tipo de usuário inválido.',
            'profile_photo.mimes' => 'A foto de perfil deve ser JPG, JPEG ou PNG.',
            'profile_photo.max' => 'A foto de perfil não pode ser maior que 2MB.',
            '*.file.max' => 'O arquivo não pode ser maior que 2MB.',
            '*.mimes' => 'Tipo de arquivo não permitido.',
        ];
    }
}