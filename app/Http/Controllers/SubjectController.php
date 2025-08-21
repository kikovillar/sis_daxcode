<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Lista todas as disciplinas
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem gerenciar disciplinas.');
        }
        
        $query = Subject::withCount(['questions', 'assessments']);
        
        // Filtro de busca
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $subjects = $query->orderBy('name')
                         ->paginate(15)
                         ->withQueryString();
        
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Mostra formulário de criação de disciplina
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem criar disciplinas.');
        }
        
        return view('subjects.create');
    }

    /**
     * Armazena nova disciplina
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem criar disciplinas.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $subject = Subject::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Disciplina criada com sucesso!');
    }

    /**
     * Mostra detalhes da disciplina
     */
    public function show(Subject $subject)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem visualizar disciplinas.');
        }
        
        $subject->load(['questions' => function($query) {
            $query->with('creator')->latest()->take(10);
        }, 'assessments' => function($query) {
            $query->with('teacher')->latest()->take(10);
        }]);
        
        $stats = $subject->getStats();
        
        return view('subjects.show', compact('subject', 'stats'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(Subject $subject)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem editar disciplinas.');
        }
        
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Atualiza disciplina
     */
    public function update(Request $request, Subject $subject)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Apenas professores e administradores podem editar disciplinas.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.show', $subject)
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    /**
     * Remove disciplina
     */
    public function destroy(Subject $subject)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Apenas administradores podem excluir disciplinas.');
        }
        
        // Verificar se há questões ou avaliações vinculadas
        $questionsCount = $subject->questions()->count();
        $assessmentsCount = $subject->assessments()->count();
        
        if ($questionsCount > 0 || $assessmentsCount > 0) {
            return back()->with('error', 
                "Não é possível excluir disciplina que possui {$questionsCount} questão(ões) e {$assessmentsCount} avaliação(ões). " .
                "Remova ou transfira o conteúdo antes de excluir."
            );
        }
        
        $subject->delete();
        
        return redirect()->route('subjects.index')
            ->with('success', 'Disciplina excluída com sucesso!');
    }

    /**
     * API para buscar disciplinas (para uso em selects)
     */
    public function search(Request $request)
    {
        $query = Subject::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $subjects = $query->orderBy('name')
                         ->limit(20)
                         ->get(['id', 'name', 'description']);
        
        return response()->json($subjects);
    }
}