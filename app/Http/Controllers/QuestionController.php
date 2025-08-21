<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    /**
     * Lista todas as questões
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Question::with(['subject', 'options']);
        
        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        
        $questions = $query->orderBy('created_at', 'desc')
                          ->paginate(15)
                          ->withQueryString();
        
        $subjects = Subject::all();
        
        return view('questions.index', compact('questions', 'subjects'));
    }

    /**
     * Mostra formulário de criação de questão
     */
    public function create()
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem criar questões.');
        }
        
        $subjects = Subject::all();
        
        return view('questions.create', compact('subjects'));
    }

    /**
     * Armazena nova questão
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem criar questões.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|numeric|min:0.1|max:100',
            'subject_id' => 'required|exists:subjects,id',
            'explanation' => 'nullable|string',
            
            // Upload de imagem
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_description' => 'nullable|string|max:255',
            
            // Para questões de múltipla escolha
            'options' => 'required_if:type,multiple_choice|array|min:2|max:6',
            'options.*.content' => 'required_if:type,multiple_choice|string|max:500',
            'options.*.is_correct' => 'nullable|boolean',
            
            // Para questões verdadeiro/falso
            'correct_answer' => 'required_if:type,true_false|boolean',
        ]);

        $question = DB::transaction(function () use ($validated, $request) {
            // Processar upload de imagem
            $imagePath = null;
            if ($request->hasFile('question_image')) {
                $imagePath = $request->file('question_image')->store('questions/images', 'public');
            }
            
            $question = Question::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'difficulty' => $validated['difficulty'],
                'points' => $validated['points'],
                'subject_id' => $validated['subject_id'],
                'explanation' => $validated['explanation'] ?? null,
                'image_path' => $imagePath,
                'image_description' => $validated['image_description'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Criar opções baseado no tipo
            if ($validated['type'] === 'multiple_choice') {
                $hasCorrect = false;
                foreach ($validated['options'] as $optionData) {
                    $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'];
                    if ($isCorrect) $hasCorrect = true;
                    
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $optionData['content'],
                        'is_correct' => $isCorrect,
                    ]);
                }
                
                if (!$hasCorrect) {
                    throw new \Exception('Pelo menos uma opção deve estar marcada como correta.');
                }
                
            } elseif ($validated['type'] === 'true_false') {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'content' => 'Verdadeiro',
                    'is_correct' => $validated['correct_answer'],
                ]);
                
                QuestionOption::create([
                    'question_id' => $question->id,
                    'content' => 'Falso',
                    'is_correct' => !$validated['correct_answer'],
                ]);
            }

            return $question;
        });

        return redirect()->route('questions.show', $question)
            ->with('success', 'Questão criada com sucesso!');
    }

    /**
     * Mostra detalhes da questão
     */
    public function show(Question $question)
    {
        $question->load(['subject', 'options', 'creator']);
        
        return view('questions.show', compact('question'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(Question $question)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem editar questões.');
        }
        
        // Verificar se o usuário pode editar esta questão
        if (!Auth::user()->isAdmin() && $question->created_by !== Auth::id()) {
            abort(403, 'Você só pode editar suas próprias questões.');
        }
        
        $question->load(['options']);
        $subjects = Subject::all();
        
        return view('questions.edit', compact('question', 'subjects'));
    }

    /**
     * Atualiza questão
     */
    public function update(Request $request, Question $question)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem editar questões.');
        }
        
        // Verificar se o usuário pode editar esta questão
        if (!Auth::user()->isAdmin() && $question->created_by !== Auth::id()) {
            abort(403, 'Você só pode editar suas próprias questões.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|numeric|min:0.1|max:100',
            'subject_id' => 'required|exists:subjects,id',
            'explanation' => 'nullable|string',
            
            // Upload de imagem
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_description' => 'nullable|string|max:255',
            'remove_image' => 'nullable|boolean',
            
            // Para questões de múltipla escolha
            'options' => 'required_if:type,multiple_choice|array|min:2|max:6',
            'options.*.content' => 'required_if:type,multiple_choice|string|max:500',
            'options.*.is_correct' => 'nullable|boolean',
            
            // Para questões verdadeiro/falso
            'correct_answer' => 'required_if:type,true_false|boolean',
        ]);

        DB::transaction(function () use ($validated, $question, $request) {
            // Processar imagem
            $updateData = [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'difficulty' => $validated['difficulty'],
                'points' => $validated['points'],
                'subject_id' => $validated['subject_id'],
                'explanation' => $validated['explanation'] ?? null,
                'image_description' => $validated['image_description'] ?? null,
            ];
            
            // Remover imagem se solicitado
            if (isset($validated['remove_image']) && $validated['remove_image']) {
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $updateData['image_path'] = null;
                $updateData['image_description'] = null;
            }
            // Upload de nova imagem
            elseif ($request->hasFile('question_image')) {
                // Remover imagem antiga se existir
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $updateData['image_path'] = $request->file('question_image')->store('questions/images', 'public');
            }
            
            $question->update($updateData);

            // Atualizar opções
            if ($question->type === 'multiple_choice') {
                // Remover opções antigas
                $question->options()->delete();
                
                $hasCorrect = false;
                foreach ($validated['options'] as $optionData) {
                    $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'];
                    if ($isCorrect) $hasCorrect = true;
                    
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'content' => $optionData['content'],
                        'is_correct' => $isCorrect,
                    ]);
                }
                
                if (!$hasCorrect) {
                    throw new \Exception('Pelo menos uma opção deve estar marcada como correta.');
                }
                
            } elseif ($question->type === 'true_false') {
                $question->options()->delete();
                
                QuestionOption::create([
                    'question_id' => $question->id,
                    'content' => 'Verdadeiro',
                    'is_correct' => $validated['correct_answer'],
                ]);
                
                QuestionOption::create([
                    'question_id' => $question->id,
                    'content' => 'Falso',
                    'is_correct' => !$validated['correct_answer'],
                ]);
            }
        });

        return redirect()->route('questions.show', $question)
            ->with('success', 'Questão atualizada com sucesso!');
    }

    /**
     * Remove questão
     */
    public function destroy(Question $question)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem excluir questões.');
        }
        
        // Verificar se o usuário pode excluir esta questão
        if (!Auth::user()->isAdmin() && $question->created_by !== Auth::id()) {
            abort(403, 'Você só pode excluir suas próprias questões.');
        }
        
        // Verificar se a questão está sendo usada em avaliações
        $assessmentsUsing = $question->assessments()->count();
        if ($assessmentsUsing > 0) {
            return back()->with('error', "Não é possível excluir questão que está sendo usada em {$assessmentsUsing} avaliação(ões).");
        }
        
        // Verificar se há respostas de alunos
        $studentAnswers = $question->studentAnswers()->count();
        if ($studentAnswers > 0) {
            return back()->with('error', "Não é possível excluir questão que possui {$studentAnswers} resposta(s) de aluno(s).");
        }

        DB::transaction(function () use ($question) {
            // Remover imagem se existir
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }
            
            // Remover opções
            $question->options()->delete();
            
            // Remover a questão
            $question->delete();
        });
        
        return redirect()->route('questions.index')
            ->with('success', 'Questão excluída com sucesso!');
    }

    /**
     * API para buscar questões (para adicionar em avaliações)
     */
    public function search(Request $request)
    {
        // Verificar se o usuário tem permissão
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        try {
            $query = Question::with(['subject', 'options']);
            
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('content', 'like', '%' . $request->search . '%');
                });
            }
            
            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->subject_id);
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('difficulty')) {
                $query->where('difficulty', $request->difficulty);
            }
            
            // Excluir questões já adicionadas à avaliação
            if ($request->filled('assessment_id')) {
                $assessmentQuestionIds = DB::table('assessment_questions')
                    ->where('assessment_id', $request->assessment_id)
                    ->pluck('question_id');
                
                $query->whereNotIn('id', $assessmentQuestionIds);
            }
            
            $questions = $query->orderBy('created_at', 'desc')
                              ->limit(20)
                              ->get();
            
            // Log para debug
            \Log::info('Question search', [
                'user_id' => Auth::id(),
                'params' => $request->all(),
                'questions_found' => $questions->count()
            ]);
            
            return response()->json($questions);
            
        } catch (\Exception $e) {
            \Log::error('Error in question search', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'params' => $request->all()
            ]);
            
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }
}