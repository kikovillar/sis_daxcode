<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserCreationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UserManagementController extends Controller
{
    public function __construct(
        private UserCreationService $userCreationService
    ) {}
    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $query = User::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        return view('admin.users.create');
    }

    /**
     * Criar novo usuário
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userCreationService->createUser($request);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuário criado com sucesso!');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Mostrar detalhes do usuário
     */
    public function show(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $userData = $this->userCreationService->prepareUserDisplayData($user);
        
        return view('admin.users.show', compact('user', 'userData'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualizar usuário
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userCreationService->updateUser($request, $user);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao atualizar usuário: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Excluir usuário
     */
    public function destroy(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Alternar status do usuário
     */
    public function toggleStatus(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        // Não permitir desativar o próprio usuário
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Você não pode alterar o status da sua própria conta.');
        }

        // Alternar o status usando email_verified_at
        $newStatus = $user->email_verified_at ? null : now();
        $user->update(['email_verified_at' => $newStatus]);

        $statusText = $newStatus ? 'ativado' : 'desativado';
        $icon = $newStatus ? '✅' : '❌';
        
        return back()->with('success', "{$icon} Usuário {$user->name} foi {$statusText} com sucesso!");
    }

    /**
     * Exportar dados do usuário para Excel
     */
    public function exportToExcel(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        // Criar nova planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar título da planilha
        $sheet->setTitle('Dados do Usuário');
        
        // Título principal
        $sheet->setCellValue('A1', 'DADOS COMPLETOS DO USUÁRIO');
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
        $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');
        
        $row = 3; // Começar na linha 3
        
        // Função para adicionar seção
        $addSection = function($title, $data) use ($sheet, &$row) {
            // Título da seção
            $sheet->setCellValue("A{$row}", $title);
            $sheet->mergeCells("A{$row}:C{$row}");
            $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle("A{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E2F3');
            $sheet->getStyle("A{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
            
            // Dados da seção
            foreach ($data as $label => $value) {
                $sheet->setCellValue("A{$row}", $label);
                $sheet->setCellValue("B{$row}", $value);
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}:B{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }
            $row++; // Linha em branco entre seções
        };

        // DADOS BÁSICOS
        $addSection('DADOS BÁSICOS', [
            'Nome Completo' => $user->name ?: 'Não informado',
            'Email' => $user->email ?: 'Não informado',
            'Tipo de Usuário' => $user->role === 'admin' ? 'Administrador' : ($user->role === 'professor' ? 'Professor' : 'Aluno'),
            'Status' => $user->email_verified_at ? 'Ativo' : 'Inativo',
            'Cadastrado em' => $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Não informado',
        ]);

        // DADOS PESSOAIS
        $addSection('DADOS PESSOAIS', [
            'Data de Nascimento' => $user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : 'Não informado',
            'Idade' => $user->idade ? $user->idade . ' anos' : 'Não informado',
            'Sexo' => $user->sexo ? ucfirst($user->sexo) : 'Não informado',
            'RG' => $user->rg ?: 'Não informado',
            'CPF' => $user->cpf ?: 'Não informado',
            'Telefone' => $user->telefone ?: 'Não informado',
            'Benefício (BF)' => $user->bf ?: 'Não informado',
            'Estado Civil' => $user->estado_civil ? ucfirst(str_replace('_', ' ', $user->estado_civil)) : 'Não informado',
            'Tamanho da Camisa' => $user->tamanho_camisa ?: 'Não informado',
            'Tem Filhos' => $user->tem_filhos ? 'Sim' . ($user->quantidade_filhos ? " ({$user->quantidade_filhos})" : '') : 'Não',
        ]);

        // ENDEREÇO
        $addSection('ENDEREÇO', [
            'Endereço' => $user->endereco ?: 'Não informado',
            'Bairro' => $user->bairro ?: 'Não informado',
            'Cidade' => $user->cidade ?: 'Não informado',
            'Estado' => $user->estado ?: 'Não informado',
            'CEP' => $user->cep ?: 'Não informado',
        ]);

        // CONTATO DE URGÊNCIA
        $addSection('CONTATO DE URGÊNCIA', [
            'Nome do Contato' => $user->urgencia_nome_contato ?: 'Não informado',
            'Telefone do Contato' => $user->urgencia_telefone_contato ?: 'Não informado',
        ]);

        // DOCUMENTOS
        $addSection('DOCUMENTOS', [
            'Documento RG' => $user->documento_rg ? 'Enviado' : 'Não enviado',
            'Documento CNH' => $user->documento_cnh ? 'Enviado' : 'Não enviado',
            'Documento CPF' => $user->documento_cpf ? 'Enviado' : 'Não enviado',
            'Foto 3x4' => $user->documento_foto_3x4 ? 'Enviado' : 'Não enviado',
        ]);

        // SAÚDE
        $addSection('INFORMAÇÕES DE SAÚDE', [
            'Possui Deficiência' => $user->tem_deficiencia ? 'Sim' : 'Não',
            'Descrição da Deficiência' => $user->descricao_deficiencia ?: 'Não informado',
            'Condição de Saúde Especial' => $user->tem_condicao_saude ? 'Sim' : 'Não',
            'Descrição da Condição' => $user->descricao_saude ?: 'Não informado',
            'Possui Alergia' => $user->tem_alergia ? 'Sim' : 'Não',
            'Descrição da Alergia' => $user->descricao_alergia ?: 'Não informado',
            'Usa Medicamento' => $user->usa_medicamento ? 'Sim' : 'Não',
            'Qual Medicamento' => $user->qual_medicamento ?: 'Não informado',
        ]);

        // EDUCAÇÃO
        $educacao = [];
        $educacao['Pré-escolar'] = $user->ensino_pre_escolar ? 'Sim' : 'Não';
        $educacao['Fundamental Concluído'] = $user->ensino_fundamental_concluido ? 'Sim' : 'Não';
        $educacao['Fundamental Cursando'] = $user->ensino_fundamental_cursando ? 'Sim' : 'Não';
        if ($user->ensino_fundamental_instituicao) {
            $educacao['Instituição Fundamental'] = $user->ensino_fundamental_instituicao;
        }
        $educacao['Médio Concluído'] = $user->ensino_medio_concluido ? 'Sim' : 'Não';
        $educacao['Médio Cursando'] = $user->ensino_medio_cursando ? 'Sim' : 'Não';
        if ($user->ensino_medio_instituicao) {
            $educacao['Instituição Médio'] = $user->ensino_medio_instituicao;
        }
        $educacao['Técnico Concluído'] = $user->ensino_tecnico_concluido ? 'Sim' : 'Não';
        $educacao['Técnico Cursando'] = $user->ensino_tecnico_cursando ? 'Sim' : 'Não';
        if ($user->ensino_tecnico_instituicao) {
            $educacao['Instituição Técnico'] = $user->ensino_tecnico_instituicao;
        }
        $educacao['Superior Concluído'] = $user->ensino_superior_concluido ? 'Sim' : 'Não';
        $educacao['Superior Cursando'] = $user->superior_cursando ? 'Sim' : 'Não';
        $educacao['Superior Trancado'] = $user->superior_trancado ? 'Sim' : 'Não';
        if ($user->superior_instituicao) {
            $educacao['Instituição Superior'] = $user->superior_instituicao;
        }
        $educacao['Pós-graduação Concluído'] = $user->pos_graduacao_concluido ? 'Sim' : 'Não';
        $educacao['Pós-graduação Cursando'] = $user->pos_graduacao_cursando ? 'Sim' : 'Não';
        if ($user->pos_graduacao_instituicao) {
            $educacao['Instituição Pós-graduação'] = $user->pos_graduacao_instituicao;
        }
        
        $addSection('EDUCAÇÃO', $educacao);

        // CURSOS EXTRAS
        $cursos = [];
        if ($user->curso_1) {
            $cursos['Curso 1'] = $user->curso_1;
            if ($user->curso_1_instituicao) {
                $cursos['Instituição Curso 1'] = $user->curso_1_instituicao;
            }
        }
        if ($user->curso_2) {
            $cursos['Curso 2'] = $user->curso_2;
            if ($user->curso_2_instituicao) {
                $cursos['Instituição Curso 2'] = $user->curso_2_instituicao;
            }
        }
        if ($user->curso_3) {
            $cursos['Curso 3'] = $user->curso_3;
            if ($user->curso_3_instituicao) {
                $cursos['Instituição Curso 3'] = $user->curso_3_instituicao;
            }
        }
        
        if (empty($cursos)) {
            $cursos['Cursos Extras'] = 'Nenhum curso informado';
        }
        
        $addSection('CURSOS EXTRAS', $cursos);

        // EXPERIÊNCIA PROFISSIONAL
        $experiencia = [];
        $experiencia['Situação Profissional'] = $user->situacao_profissional ? ucfirst($user->situacao_profissional) : 'Não informado';
        
        if ($user->experiencia_1_instituicao || $user->experiencia_1_funcao) {
            $experiencia['Empresa 1'] = $user->experiencia_1_instituicao ?: 'Não informado';
            $experiencia['Período 1'] = $user->experiencia_1_ano ?: 'Não informado';
            $experiencia['Função 1'] = $user->experiencia_1_funcao ?: 'Não informado';
            $experiencia['Atividades 1'] = $user->experiencia_1_atividades ?: 'Não informado';
        }
        
        if ($user->experiencia_2_instituicao || $user->experiencia_2_funcao) {
            $experiencia['Empresa 2'] = $user->experiencia_2_instituicao ?: 'Não informado';
            $experiencia['Período 2'] = $user->experiencia_2_ano ?: 'Não informado';
            $experiencia['Função 2'] = $user->experiencia_2_funcao ?: 'Não informado';
            $experiencia['Atividades 2'] = $user->experiencia_2_atividades ?: 'Não informado';
        }
        
        $addSection('EXPERIÊNCIA PROFISSIONAL', $experiencia);

        // HABILIDADES
        $addSection('HABILIDADES', [
            'Nível de Inglês' => $user->nivel_ingles ? ucfirst($user->nivel_ingles) : 'Não informado',
            'Desenvolveu Sistemas' => $user->desenvolveu_sistemas ? 'Sim' : 'Não',
            'Já Empreendeu' => $user->ja_empreendeu ? 'Sim' : 'Não',
        ]);

        // DISPONIBILIDADE
        $dias = null;
        if ($user->disponibilidade_dias) {
            $dias = is_string($user->disponibilidade_dias) ? json_decode($user->disponibilidade_dias, true) : $user->disponibilidade_dias;
        }
        $diasTexto = $dias ? implode(', ', array_map('ucfirst', $dias)) : 'Nenhum dia informado';
        
        $addSection('DISPONIBILIDADE', [
            'Dias Disponíveis' => $diasTexto,
            'Horário' => $user->disponibilidade_horario ?: 'Não informado',
        ]);

        // Ajustar largura das colunas
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);

        // Configurar cabeçalhos para download
        $fileName = 'usuario_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}