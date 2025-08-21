<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Lista todas as turmas
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = ClassModel::with(['teacher', 'students']);
        
        // Filtros baseados no tipo de usuário
        if ($user->isTeacher()) {
            // Professores veem apenas suas turmas
            $query->where('teacher_id', $user->id);
        } elseif (!$user->isAdmin()) {
            // Outros usuários não têm acesso
            abort(403, 'Acesso negado.');
        }
        
        // Filtros de busca
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        $classes = $query->orderBy('created_at', 'desc')
                        ->paginate(15)
                        ->withQueryString();
        
        // Para administradores, buscar todos os professores para filtro
        $teachers = $user->isAdmin() ? 
            User::where('role', 'professor')->orderBy('name')->get() : 
            collect();
        
        return view('classes.index', compact('classes', 'teachers'));
    }

    /**
     * Mostra formulário de criação de turma
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem criar turmas.');
        }
        
        // Para administradores, buscar todos os professores
        $teachers = $user->isAdmin() ? 
            User::where('role', 'professor')->orderBy('name')->get() : 
            collect([$user]);
        
        return view('classes.create', compact('teachers'));
    }

    /**
     * Armazena nova turma
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem criar turmas.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'teacher_id' => $user->isAdmin() ? 'required|exists:users,id' : 'nullable',
        ]);

        // Se for professor, usar seu próprio ID
        if ($user->isTeacher()) {
            $validated['teacher_id'] = $user->id;
        }
        
        // Verificar se o teacher_id é realmente um professor
        $teacher = User::find($validated['teacher_id']);
        if (!$teacher || !$teacher->isTeacher()) {
            return back()->withErrors(['teacher_id' => 'O usuário selecionado deve ser um professor.']);
        }

        $class = ClassModel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'teacher_id' => $validated['teacher_id'],
        ]);

        return redirect()->route('classes.show', $class)
            ->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Mostra detalhes da turma
     */
    public function show(ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para ver esta turma.');
        }
        
        $class->load(['teacher', 'students', 'assessments']);
        
        // Buscar alunos disponíveis para adicionar à turma
        $availableStudents = User::where('role', 'aluno')
            ->whereNotIn('id', $class->students->pluck('id'))
            ->orderBy('name')
            ->get();
        
        $stats = $class->getStats();
        
        return view('classes.show', compact('class', 'availableStudents', 'stats'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para editar esta turma.');
        }
        
        // Para administradores, buscar todos os professores
        $teachers = $user->isAdmin() ? 
            User::where('role', 'professor')->orderBy('name')->get() : 
            collect([$user]);
        
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Atualiza turma
     */
    public function update(Request $request, ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para editar esta turma.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'teacher_id' => $user->isAdmin() ? 'required|exists:users,id' : 'nullable',
        ]);

        // Se for professor, manter seu próprio ID
        if ($user->isTeacher()) {
            $validated['teacher_id'] = $user->id;
        }
        
        // Verificar se o teacher_id é realmente um professor
        $teacher = User::find($validated['teacher_id']);
        if (!$teacher || !$teacher->isTeacher()) {
            return back()->withErrors(['teacher_id' => 'O usuário selecionado deve ser um professor.']);
        }

        $class->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'teacher_id' => $validated['teacher_id'],
        ]);

        return redirect()->route('classes.show', $class)
            ->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove turma
     */
    public function destroy(ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões - apenas admin pode excluir turmas
        if (!$user->isAdmin()) {
            abort(403, 'Apenas administradores podem excluir turmas.');
        }
        
        // Verificar se há avaliações associadas
        $assessmentsCount = $class->assessments()->count();
        if ($assessmentsCount > 0) {
            return back()->with('error', "Não é possível excluir turma com {$assessmentsCount} avaliação(ões) associada(s).");
        }
        
        // Verificar se há alunos matriculados
        $studentsCount = $class->students()->count();
        if ($studentsCount > 0) {
            return back()->with('error', "Não é possível excluir turma com {$studentsCount} aluno(s) matriculado(s). Remova os alunos primeiro.");
        }

        DB::transaction(function () use ($class) {
            // Remover relacionamentos
            $class->students()->detach();
            $class->assessments()->detach();
            
            // Excluir a turma
            $class->delete();
        });
        
        return redirect()->route('classes.index')
            ->with('success', 'Turma excluída com sucesso!');
    }

    /**
     * Adiciona aluno à turma
     */
    public function addStudent(Request $request, ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para gerenciar esta turma.');
        }
        
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);
        
        $student = User::find($validated['student_id']);
        
        if (!$student->isStudent()) {
            return back()->with('error', 'O usuário selecionado deve ser um aluno.');
        }
        
        if ($class->hasStudent($student)) {
            return back()->with('error', 'Este aluno já está matriculado na turma.');
        }
        
        $class->enrollStudent($student);
        
        return back()->with('success', "Aluno {$student->name} adicionado à turma com sucesso!");
    }

    /**
     * Remove aluno da turma
     */
    public function removeStudent(ClassModel $class, User $student)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para gerenciar esta turma.');
        }
        
        if (!$class->hasStudent($student)) {
            return back()->with('error', 'Este aluno não está matriculado na turma.');
        }
        
        $class->unenrollStudent($student);
        
        return back()->with('success', "Aluno {$student->name} removido da turma com sucesso!");
    }

    /**
     * Adiciona múltiplos alunos à turma
     */
    public function addMultipleStudents(Request $request, ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para gerenciar esta turma.');
        }
        
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);
        
        $addedCount = 0;
        $alreadyEnrolled = [];
        $invalidUsers = [];
        
        foreach ($validated['student_ids'] as $studentId) {
            $student = User::find($studentId);
            
            if (!$student->isStudent()) {
                $invalidUsers[] = $student->name;
                continue;
            }
            
            if ($class->hasStudent($student)) {
                $alreadyEnrolled[] = $student->name;
                continue;
            }
            
            $class->enrollStudent($student);
            $addedCount++;
        }
        
        $message = "✅ {$addedCount} aluno(s) adicionado(s) com sucesso!";
        
        if (!empty($alreadyEnrolled)) {
            $message .= " ⚠️ Já matriculados: " . implode(', ', $alreadyEnrolled);
        }
        
        if (!empty($invalidUsers)) {
            $message .= " ❌ Usuários inválidos: " . implode(', ', $invalidUsers);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Remove múltiplos alunos da turma
     */
    public function removeMultipleStudents(Request $request, ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para gerenciar esta turma.');
        }
        
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);
        
        $removedCount = 0;
        $notEnrolled = [];
        
        foreach ($validated['student_ids'] as $studentId) {
            $student = User::find($studentId);
            
            if (!$class->hasStudent($student)) {
                $notEnrolled[] = $student->name;
                continue;
            }
            
            $class->unenrollStudent($student);
            $removedCount++;
        }
        
        $message = "✅ {$removedCount} aluno(s) removido(s) com sucesso!";
        
        if (!empty($notEnrolled)) {
            $message .= " ⚠️ Não estavam matriculados: " . implode(', ', $notEnrolled);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Transfere aluno para outra turma
     */
    public function transferStudent(Request $request, ClassModel $class, User $student)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para gerenciar esta turma.');
        }
        
        $validated = $request->validate([
            'target_class_id' => 'required|exists:classes,id|different:' . $class->id,
        ]);
        
        $targetClass = ClassModel::find($validated['target_class_id']);
        
        // Verificar se o usuário tem permissão na turma de destino
        if (!$user->isAdmin() && $user->id !== $targetClass->teacher_id) {
            return back()->with('error', 'Você não tem permissão para adicionar alunos na turma de destino.');
        }
        
        if (!$class->hasStudent($student)) {
            return back()->with('error', 'Este aluno não está matriculado na turma atual.');
        }
        
        if ($targetClass->hasStudent($student)) {
            return back()->with('error', 'Este aluno já está matriculado na turma de destino.');
        }
        
        DB::transaction(function () use ($class, $targetClass, $student) {
            $class->unenrollStudent($student);
            $targetClass->enrollStudent($student);
        });
        
        return back()->with('success', "Aluno {$student->name} transferido para a turma {$targetClass->name} com sucesso!");
    }
    
    /**
     * Mostra página de gerenciamento de alunos
     */
    public function manageStudents(ClassModel $class)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if (!$user->isAdmin() && $user->id !== $class->teacher_id) {
            abort(403, 'Você não tem permissão para gerenciar esta turma.');
        }
        
        $class->load(['teacher', 'students']);
        
        // Buscar alunos disponíveis para adicionar à turma
        $availableStudents = User::where('role', 'aluno')
            ->whereNotIn('id', $class->students->pluck('id'))
            ->orderBy('name')
            ->get();
        
        // Buscar outras turmas para transferência (apenas as que o usuário pode gerenciar)
        $transferClasses = ClassModel::where('id', '!=', $class->id);
        
        if ($user->isTeacher()) {
            $transferClasses->where('teacher_id', $user->id);
        }
        
        $transferClasses = $transferClasses->orderBy('name')->get();
        
        return view('classes.manage-students', compact('class', 'availableStudents', 'transferClasses'));
    }

    /**
     * API para buscar turmas (para uso em selects)
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        
        $query = ClassModel::with(['teacher']);
        
        // Filtros baseados no tipo de usuário
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        } elseif (!$user->isAdmin()) {
            return response()->json([]);
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        $classes = $query->orderBy('name')
                        ->limit(20)
                        ->get();
        
        return response()->json($classes);
    }
}