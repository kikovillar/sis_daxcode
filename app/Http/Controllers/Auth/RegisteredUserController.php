<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:aluno,professor'],
            
            // Documentos
            'documento_rg' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'documento_cnh' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'documento_cpf' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'documento_foto_3x4' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            
            // Dados Pessoais
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'idade' => ['nullable', 'integer', 'min:1', 'max:120'],
            'rg' => ['nullable', 'string', 'max:20'],
            'cpf' => ['nullable', 'string', 'max:14'],
            'sexo' => ['nullable', 'in:masculino,feminino,outro'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'bf' => ['nullable', 'string', 'max:255'],
            'estado_civil' => ['nullable', 'in:solteiro,casado,divorciado,viuvo,uniao_estavel'],
            'tem_filhos' => ['nullable', 'boolean'],
            'quantidade_filhos' => ['nullable', 'integer', 'min:0', 'max:20'],
            
            // Endereco
            'endereco' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:2'],
            'cep' => ['nullable', 'string', 'max:10'],
            
            // Contato de Urgencia
            'urgencia_nome_contato' => ['nullable', 'string', 'max:255'],
            'urgencia_telefone_contato' => ['nullable', 'string', 'max:20'],
            
            // Informacoes Fisicas
            'tamanho_camisa' => ['nullable', 'in:PP,P,M,G,GG,XG,XXG'],
            
            // Saude
            'tem_deficiencia' => ['nullable', 'boolean'],
            'descricao_deficiencia' => ['nullable', 'string', 'max:1000'],
            'tem_condicao_saude' => ['nullable', 'boolean'],
            'descricao_saude' => ['nullable', 'string', 'max:1000'],
            'tem_alergia' => ['nullable', 'boolean'],
            'descricao_alergia' => ['nullable', 'string', 'max:1000'],
            'usa_medicamento' => ['nullable', 'boolean'],
            'qual_medicamento' => ['nullable', 'string', 'max:1000'],
            
            // Educacao
            'ensino_pre_escolar' => ['nullable', 'boolean'],
            'ensino_fundamental_concluido' => ['nullable', 'boolean'],
            'ensino_fundamental_cursando' => ['nullable', 'boolean'],
            'ensino_fundamental_instituicao' => ['nullable', 'string', 'max:255'],
            'ensino_medio_concluido' => ['nullable', 'boolean'],
            'ensino_medio_cursando' => ['nullable', 'boolean'],
            'ensino_medio_instituicao' => ['nullable', 'string', 'max:255'],
            'ensino_tecnico_concluido' => ['nullable', 'boolean'],
            'ensino_tecnico_cursando' => ['nullable', 'boolean'],
            'ensino_tecnico_instituicao' => ['nullable', 'string', 'max:255'],
            'ensino_superior_concluido' => ['nullable', 'boolean'],
            'superior_cursando' => ['nullable', 'boolean'],
            'superior_trancado' => ['nullable', 'boolean'],
            'superior_instituicao' => ['nullable', 'string', 'max:255'],
            'pos_graduacao_concluido' => ['nullable', 'boolean'],
            'pos_graduacao_cursando' => ['nullable', 'boolean'],
            'pos_graduacao_instituicao' => ['nullable', 'string', 'max:255'],
            
            // Cursos Extras
            'curso_1' => ['nullable', 'string', 'max:255'],
            'curso_1_instituicao' => ['nullable', 'string', 'max:255'],
            'curso_2' => ['nullable', 'string', 'max:255'],
            'curso_2_instituicao' => ['nullable', 'string', 'max:255'],
            'curso_3' => ['nullable', 'string', 'max:255'],
            'curso_3_instituicao' => ['nullable', 'string', 'max:255'],
            
            // Profissional
            'situacao_profissional' => ['nullable', 'in:empregado,desempregado,autonomo,estudante,aposentado'],
            'experiencia_1_instituicao' => ['nullable', 'string', 'max:255'],
            'experiencia_1_ano' => ['nullable', 'string', 'max:10'],
            'experiencia_1_funcao' => ['nullable', 'string', 'max:255'],
            'experiencia_1_atividades' => ['nullable', 'string', 'max:1000'],
            'experiencia_2_instituicao' => ['nullable', 'string', 'max:255'],
            'experiencia_2_ano' => ['nullable', 'string', 'max:10'],
            'experiencia_2_funcao' => ['nullable', 'string', 'max:255'],
            'experiencia_2_atividades' => ['nullable', 'string', 'max:1000'],
            
            // Habilidades
            'nivel_ingles' => ['nullable', 'in:basico,avancado,fluente'],
            'desenvolveu_sistemas' => ['nullable', 'boolean'],
            'ja_empreendeu' => ['nullable', 'boolean'],
            
            // Disponibilidade
            'disponibilidade_dias' => ['nullable', 'array'],
            'disponibilidade_dias.*' => ['string', 'in:segunda,terca,quarta,quinta,sexta,sabado,domingo'],
            'disponibilidade_horario' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($validationRules);

        // Handle file uploads
        $fileFields = ['documento_rg', 'documento_cnh', 'documento_cpf', 'documento_foto_3x4'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('documents', 'public');
            }
        }

        // Remove password confirmation from validated data
        unset($validated['password_confirmation']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            ...$validated
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}