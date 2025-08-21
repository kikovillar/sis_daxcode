<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Question;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    /**
     * Lista todas as avaliações (para professores)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Assessment::query();
        
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        } elseif ($user->isStudent()) {
            $query->whereHas('classes.students', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        // Filtros
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $assessments = $query->with(['subject', 'classes', 'studentAssessments'])
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('assessments.index', compact('assessments'));
    }

    /**
     * Mostra formulário de criação de avaliação
     */
    public function create()
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem criar avaliações.');
        }
        
        $user = Auth::user();
        $subjects = Subject::all();
        
        // Para administradores, mostrar todas as turmas
        // Para professores, mostrar apenas suas turmas
        if ($user->isAdmin()) {
            $classes = ClassModel::with(['teacher', 'students'])->orderBy('name')->get();
        } else {
            $classes = ClassModel::where('teacher_id', $user->id)->with(['students'])->orderBy('name')->get();
        }
        
        return view('assessments.create', compact('subjects', 'classes'));
    }

    /**
     * Armazena nova avaliação
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem criar avaliações.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'max_score' => 'required|numeric|min:0',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'subject_id' => 'required|exists:subjects,id',
            'classes' => 'required|array|min:1',
            'classes.*' => 'exists:classes,id',
            'action' => 'required|in:draft,publish'
        ]);

        $assessment = DB::transaction(function () use ($validated, $request) {
            $status = $validated['action'] === 'publish' ? 'published' : 'draft';
            
            $assessment = Assessment::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'max_score' => $validated['max_score'],
                'opens_at' => $validated['opens_at'],
                'closes_at' => $validated['closes_at'],
                'subject_id' => $validated['subject_id'],
                'teacher_id' => Auth::id(),
                'status' => $status,
            ]);

            // Verificar se o professor tem acesso às turmas selecionadas
            $userClasses = ClassModel::where('teacher_id', Auth::id())
                ->whereIn('id', $validated['classes'])
                ->pluck('id');

            $assessment->classes()->attach($userClasses);
            
            return $assessment;
        });

        $message = $validated['action'] === 'publish' ? 
            'Avaliação criada e publicada com sucesso!' : 
            'Avaliação salva como rascunho!';

        return redirect()->route('assessments.index')
            ->with('success', $message);
    }

    /**
     * Mostra detalhes da avaliação
     */
    public function show(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $assessment->load(['subject', 'teacher', 'classes', 'questions.options', 'studentAssessments.student']);
        
        $stats = $assessment->getStats();
        
        return view('assessments.show', compact('assessment', 'stats'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $user = Auth::user();
        $subjects = Subject::all();
        
        // Para administradores, mostrar todas as turmas
        // Para professores, mostrar apenas suas turmas
        if ($user->isAdmin()) {
            $classes = ClassModel::with(['teacher', 'students'])->orderBy('name')->get();
        } else {
            $classes = ClassModel::where('teacher_id', $user->id)->with(['students'])->orderBy('name')->get();
        }
        
        // Carregar questões disponíveis para adicionar
        $availableQuestions = Question::with(['subject', 'options'])
            ->whereNotIn('id', $assessment->questions->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $assessment->load(['classes', 'questions.options', 'subject']);
        
        return view('assessments.edit', compact('assessment', 'subjects', 'classes', 'availableQuestions'));
    }

    /**
     * Atualiza avaliação
     */
    public function update(Request $request, Assessment $assessment)
    {
        \Log::info('=== MÉTODO UPDATE CHAMADO ===', [
            'assessment_id' => $assessment->id,
            'user_id' => Auth::id(),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'all_request_data' => $request->all()
        ]);
        
        $this->authorize('update', $assessment);
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'duration_minutes' => 'required|integer|min:1|max:480',
                'max_score' => 'required|numeric|min:0',
                'opens_at' => 'required|date',
                'closes_at' => 'required|date|after:opens_at',
                'subject_id' => 'required|exists:subjects,id',
                'class_ids' => 'required|array|min:1',
                'class_ids.*' => 'exists:classes,id',
            ]);

            DB::transaction(function () use ($assessment, $validated) {
                // Log para debug
                \Log::info('Atualizando avaliação', [
                    'assessment_id' => $assessment->id,
                    'user_id' => Auth::id(),
                    'validated_data' => $validated
                ]);

                $assessment->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'max_score' => $validated['max_score'],
                    'opens_at' => $validated['opens_at'],
                    'closes_at' => $validated['closes_at'],
                    'subject_id' => $validated['subject_id'],
                ]);

                // Atualizar turmas
                if (Auth::user()->isAdmin()) {
                    // Administradores podem associar qualquer turma
                    $assessment->classes()->sync($validated['class_ids']);
                    \Log::info('Turmas sincronizadas (admin)', ['class_ids' => $validated['class_ids']]);
                } else {
                    // Professores só podem associar suas próprias turmas
                    $userClasses = ClassModel::where('teacher_id', Auth::id())
                        ->whereIn('id', $validated['class_ids'])
                        ->pluck('id');
                    $assessment->classes()->sync($userClasses);
                    \Log::info('Turmas sincronizadas (professor)', ['user_classes' => $userClasses->toArray()]);
                }
            });

            return redirect()->route('assessments.show', $assessment)
                ->with('success', 'Avaliação atualizada com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erro de validação na atualização da avaliação', [
                'assessment_id' => $assessment->id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Erro de validação. Verifique os campos e tente novamente.');
                
        } catch (\Exception $e) {
            \Log::error('Erro na atualização da avaliação', [
                'assessment_id' => $assessment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro interno. Tente novamente ou contate o administrador.');
        }
    }

    /**
     * Remove avaliação
     */
    public function destroy(Assessment $assessment)
    {
        $this->authorize('delete', $assessment);
        
        // Verificar se há tentativas de alunos
        $studentAttempts = $assessment->studentAssessments()->count();
        if ($studentAttempts > 0) {
            return back()->with('error', "Não é possível excluir avaliação com {$studentAttempts} tentativa(s) de aluno(s). Para excluir, primeiro remova todas as tentativas.");
        }

        // Verificar se a avaliação está publicada e tem data futura
        if ($assessment->status === 'published' && $assessment->opens_at > now()) {
            return back()->with('warning', 'Avaliação publicada com data futura. Considere despublicar ao invés de excluir.');
        }

        DB::transaction(function () use ($assessment) {
            // Remover relacionamentos
            $assessment->questions()->detach();
            $assessment->classes()->detach();
            
            // Excluir a avaliação
            $assessment->delete();
        });
        
        return redirect()->route('assessments.index')
            ->with('success', 'Avaliação excluída com sucesso!');
    }

    /**
     * Publica avaliação
     */
    public function publish(Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        if ($assessment->questions()->count() === 0) {
            return back()->with('error', 'Adicione pelo menos uma questão antes de publicar.');
        }

        $assessment->publish();
        
        return back()->with('success', 'Avaliação publicada com sucesso!');
    }

    /**
     * Fecha avaliação
     */
    public function close(Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $assessment->close();
        
        return back()->with('success', 'Avaliação fechada com sucesso!');
    }

    /**
     * Inicia avaliação para aluno
     */
    public function start(Assessment $assessment)
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Apenas alunos podem fazer avaliações.');
        }

        if (!$assessment->canBeAttemptedBy($user)) {
            abort(403, 'Você não pode fazer esta avaliação.');
        }

        // Verificar se já existe tentativa ativa
        $activeAttempt = $assessment->getActiveAttemptFor($user);
        if ($activeAttempt) {
            return redirect()->route('assessments.take', $assessment);
        }

        // Verificar se já completou
        if ($assessment->isCompletedBy($user)) {
            return redirect()->route('assessments.result', $assessment)
                ->with('info', 'Você já completou esta avaliação.');
        }

        return view('assessments.start', compact('assessment'));
    }

    /**
     * Interface de realização da avaliação
     */
    public function take(Assessment $assessment)
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Apenas alunos podem fazer avaliações.');
        }

        if (!$assessment->canBeAttemptedBy($user)) {
            abort(403, 'Você não pode fazer esta avaliação.');
        }

        return view('assessments.take', compact('assessment'));
    }

    /**
     * Mostra resultado da avaliação para o aluno
     */
    public function result(Assessment $assessment)
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Acesso negado.');
        }

        $studentAssessment = $assessment->studentAssessments()
            ->where('student_id', $user->id)
            ->where('status', '!=', 'in_progress')
            ->with(['answers.question.options', 'answers.selectedOption'])
            ->first();

        if (!$studentAssessment) {
            abort(404, 'Resultado não encontrado.');
        }

        return view('assessments.result', compact('assessment', 'studentAssessment'));
    }

    /**
     * Adiciona questões à avaliação
     */
    public function addQuestions(Request $request, Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $validated = $request->validate([
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $questions = Question::whereIn('id', $validated['question_ids'])->get();
        $maxOrder = $assessment->questions()->max('assessment_questions.order') ?? 0;

        $addedCount = 0;
        foreach ($questions as $index => $question) {
            // Verificar se a questão já não está na avaliação
            if (!$assessment->questions()->where('question_id', $question->id)->exists()) {
                $assessment->questions()->attach($question->id, [
                    'order' => $maxOrder + $index + 1,
                    'points_override' => null
                ]);
                $addedCount++;
            }
        }

        // Recalcular pontuação máxima
        // $totalScore = $assessment->questions()->sum(function ($q) {
        //     return $q->pivot->points_override ?? $q->points;
        // });

        $totalPoints = $assessment->questions()->sum('points'); 

        $assessment->update(['max_score' => $totalPoints]);

        if ($addedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "$addedCount questão(ões) adicionada(s) com sucesso!"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma questão nova foi adicionada.'
            ]);
        }
    }

    /**
     * Remove questão da avaliação
     */
    public function removeQuestion(Assessment $assessment, Question $question)
    {
        $this->authorize('update', $assessment);
        
        $assessment->questions()->detach($question->id);
        
        return back()->with('success', 'Questão removida da avaliação.');
    }

    /**
     * Reordena questões da avaliação
     */
    public function reorderQuestions(Request $request, Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $validated = $request->validate([
            'question_orders' => 'required|array',
            'question_orders.*' => 'integer|min:1',
        ]);

        foreach ($validated['question_orders'] as $questionId => $order) {
            $assessment->questions()->updateExistingPivot($questionId, ['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}