<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AssessmentQuestionController extends Controller
{
    /**
     * Lista todas as avaliações para gerenciamento de questões
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Assessment::query();
        
        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        } elseif ($user->isStudent()) {
            // Estudantes não têm acesso a esta funcionalidade
            abort(403);
        }
        
        // Filtros
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        $assessments = $query->with(['subject', 'questions'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $subjects = \App\Models\Subject::orderBy('name')->get();
        
        return view('assessment-questions.index', compact('assessments', 'subjects'));
    }

    /**
     * Mostra interface de gerenciamento de questões da avaliação
     */
    public function manage(Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $assessment->load(['questions' => function($query) {
            $query->orderBy('assessment_questions.order');
        }, 'questions.options', 'questions.subject']);
        
        // Buscar questões disponíveis para adicionar
        $availableQuestions = Question::with(['subject', 'options'])
            ->where('subject_id', $assessment->subject_id)
            ->whereNotIn('id', $assessment->questions->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('assessments.manage-questions', compact('assessment', 'availableQuestions'));
    }

    /**
     * Adiciona questões à avaliação (múltiplas)
     */
    public function addQuestion(Request $request, Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $validated = $request->validate([
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:questions,id',
        ]);
        
        $addedCount = 0;
        $maxOrder = $assessment->questions()->max('assessment_questions.order') ?? 0;
        
        foreach ($validated['question_ids'] as $questionId) {
            $question = Question::findOrFail($questionId);
            
            // Verificar se a questão já está na avaliação
            if (!$assessment->questions()->where('question_id', $question->id)->exists()) {
                $maxOrder++;
                $assessment->questions()->attach($question->id, [
                    'order' => $maxOrder,
                    'points_override' => null,
                ]);
                $addedCount++;
            }
        }
        
        // Recalcular pontuação total
        $this->updateAssessmentMaxScore($assessment);
        
        if ($addedCount > 0) {
            return back()->with('success', "$addedCount questão(ões) adicionada(s) com sucesso!");
        } else {
            return back()->with('error', 'Nenhuma questão nova foi adicionada (todas já estavam na avaliação).');
        }
    }

    /**
     * Remove questão da avaliação
     */
    public function removeQuestion(Assessment $assessment, Question $question)
    {
        $this->authorize('update', $assessment);
        
        // Verificar se há tentativas de alunos
        if ($assessment->studentAssessments()->exists()) {
            return back()->with('error', 'Não é possível remover questões de avaliações com tentativas de alunos');
        }
        
        $assessment->questions()->detach($question->id);
        
        // Reordenar questões restantes
        $this->autoReorderQuestions($assessment);
        
        // Recalcular pontuação total
        $this->updateAssessmentMaxScore($assessment);
        
        return back()->with('success', 'Questão removida com sucesso!');
    }

    /**
     * Atualiza configurações de uma questão na avaliação
     */
    public function updateQuestionSettings(Request $request, Assessment $assessment, Question $question)
    {
        $this->authorize('update', $assessment);
        
        $validated = $request->validate([
            'points_override' => 'nullable|numeric|min:0.1|max:100',
            'order' => 'nullable|integer|min:1',
            'is_required' => 'boolean',
            'show_in_random' => 'boolean',
        ]);
        
        // Verificar se há tentativas de alunos para mudanças críticas
        if ($assessment->studentAssessments()->exists() && 
            (isset($validated['points_override']) || isset($validated['order']))) {
            return response()->json([
                'error' => 'Não é possível alterar pontuação ou ordem em avaliações com tentativas de alunos'
            ], 400);
        }
        
        $pivotData = [];
        if (isset($validated['points_override'])) {
            $pivotData['points_override'] = $validated['points_override'];
        }
        if (isset($validated['order'])) {
            $pivotData['order'] = $validated['order'];
        }
        if (isset($validated['is_required'])) {
            $pivotData['is_required'] = $validated['is_required'];
        }
        if (isset($validated['show_in_random'])) {
            $pivotData['show_in_random'] = $validated['show_in_random'];
        }
        
        $assessment->questions()->updateExistingPivot($question->id, $pivotData);
        
        // Se mudou a ordem, reordenar
        if (isset($validated['order'])) {
            $this->autoReorderQuestions($assessment);
        }
        
        // Recalcular pontuação total
        $this->updateAssessmentMaxScore($assessment);
        
        return response()->json([
            'success' => true,
            'message' => 'Configurações atualizadas com sucesso!'
        ]);
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
        
        // Verificar se há tentativas de alunos
        if ($assessment->studentAssessments()->exists()) {
            return response()->json([
                'error' => 'Não é possível reordenar questões em avaliações com tentativas de alunos'
            ], 400);
        }
        
        DB::transaction(function () use ($assessment, $validated) {
            foreach ($validated['question_orders'] as $questionId => $order) {
                $assessment->questions()->updateExistingPivot($questionId, ['order' => $order]);
            }
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Ordem das questões atualizada com sucesso!'
        ]);
    }

    /**
     * Upload de imagem para questão
     */
    public function uploadQuestionImage(Request $request, Assessment $assessment, Question $question)
    {
        $this->authorize('update', $assessment);
        
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'image_description' => 'nullable|string|max:255',
        ]);
        
        try {
            // Upload da imagem
            $imagePath = $request->file('image')->store('questions/images', 'public');
            
            // Atualizar questão com caminho da imagem
            $question->update([
                'image_path' => $imagePath,
                'image_description' => $validated['image_description'] ?? null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Imagem enviada com sucesso!',
                'image_url' => Storage::url($imagePath)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao fazer upload da imagem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove imagem da questão
     */
    public function removeQuestionImage(Assessment $assessment, Question $question)
    {
        $this->authorize('update', $assessment);
        
        try {
            // Remover arquivo do storage
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }
            
            // Limpar campos da questão
            $question->update([
                'image_path' => null,
                'image_description' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Imagem removida com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao remover imagem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplica questão dentro da avaliação
     */
    public function duplicateQuestion(Assessment $assessment, Question $question)
    {
        $this->authorize('update', $assessment);
        
        try {
            DB::transaction(function () use ($assessment, $question) {
                // Criar nova questão baseada na original
                $newQuestion = $question->replicate();
                $newQuestion->title = $question->title . ' (Cópia)';
                $newQuestion->created_by = Auth::id();
                $newQuestion->save();
                
                // Duplicar opções se existirem
                foreach ($question->options as $option) {
                    $newOption = $option->replicate();
                    $newOption->question_id = $newQuestion->id;
                    $newOption->save();
                }
                
                // Adicionar à avaliação
                $nextOrder = $assessment->questions()->max('assessment_questions.order') + 1;
                $originalPivot = $assessment->questions()->where('question_id', $question->id)->first()->pivot;
                
                $assessment->questions()->attach($newQuestion->id, [
                    'order' => $nextOrder,
                    'points_override' => $originalPivot->points_override,
                    'is_required' => $originalPivot->is_required ?? true,
                    'show_in_random' => $originalPivot->show_in_random ?? true,
                ]);
            });
            
            // Recalcular pontuação total
            $this->updateAssessmentMaxScore($assessment);
            
            return response()->json([
                'success' => true,
                'message' => 'Questão duplicada com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao duplicar questão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza pontuação máxima da avaliação
     */
    private function updateAssessmentMaxScore(Assessment $assessment)
    {
        // Carregar questões com pivot data
        $questions = $assessment->questions()->get();
        
        $totalScore = $questions->sum(function ($question) {
            return $question->pivot->points_override ?? $question->points;
        });
        
        $assessment->update(['max_score' => $totalScore]);
    }

    /**
     * Reordena questões automaticamente (método privado)
     */
    private function autoReorderQuestions(Assessment $assessment)
    {
        $questions = $assessment->questions()->orderBy('assessment_questions.order')->get();
        
        foreach ($questions as $index => $question) {
            $assessment->questions()->updateExistingPivot($question->id, [
                'order' => $index + 1
            ]);
        }
    }

    /**
     * Exporta questões da avaliação
     */
    public function exportQuestions(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $assessment->load(['questions.options', 'questions.subject']);
        
        $data = [
            'assessment' => [
                'title' => $assessment->title,
                'description' => $assessment->description,
                'subject' => $assessment->subject->name,
                'max_score' => $assessment->max_score,
                'duration_minutes' => $assessment->duration_minutes,
            ],
            'questions' => $assessment->questions->map(function ($question) {
                return [
                    'order' => $question->pivot->order,
                    'title' => $question->title,
                    'content' => $question->content,
                    'type' => $question->type,
                    'difficulty' => $question->difficulty,
                    'points' => $question->pivot->points_override ?? $question->points,
                    'explanation' => $question->explanation,
                    'options' => $question->options->map(function ($option) {
                        return [
                            'content' => $option->content,
                            'is_correct' => $option->is_correct,
                            'order' => $option->order,
                        ];
                    }),
                ];
            }),
        ];
        
        return response()->json($data);
    }
}