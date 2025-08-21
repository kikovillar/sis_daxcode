<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionBulkController extends Controller
{
    /**
     * Operações em lote para questões
     */
    public function bulkActions(Request $request)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem realizar operações em lote.');
        }
        
        $validated = $request->validate([
            'action' => 'required|in:delete,duplicate,export,change_subject,change_difficulty',
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:questions,id',
            'target_subject_id' => 'nullable|exists:subjects,id',
            'target_difficulty' => 'nullable|in:easy,medium,hard',
        ]);
        
        $questions = Question::whereIn('id', $validated['question_ids'])->get();
        
        // Verificar permissões
        foreach ($questions as $question) {
            if (!Auth::user()->isAdmin() && $question->created_by !== Auth::id()) {
                return back()->with('error', 'Você só pode modificar suas próprias questões.');
            }
        }
        
        switch ($validated['action']) {
            case 'delete':
                return $this->bulkDelete($questions);
            case 'duplicate':
                return $this->bulkDuplicate($questions);
            case 'export':
                return $this->bulkExport($questions);
            case 'change_subject':
                return $this->bulkChangeSubject($questions, $validated['target_subject_id']);
            case 'change_difficulty':
                return $this->bulkChangeDifficulty($questions, $validated['target_difficulty']);
        }
    }
    
    /**
     * Exclusão em lote
     */
    private function bulkDelete($questions)
    {
        $cannotDelete = [];
        $deleted = 0;
        
        foreach ($questions as $question) {
            // Verificar se pode excluir
            if ($question->assessments()->exists() || $question->studentAnswers()->exists()) {
                $cannotDelete[] = $question->title;
                continue;
            }
            
            DB::transaction(function () use ($question) {
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $question->options()->delete();
                $question->delete();
            });
            
            $deleted++;
        }
        
        $message = "{$deleted} questão(ões) excluída(s) com sucesso!";
        if (!empty($cannotDelete)) {
            $message .= " Não foi possível excluir: " . implode(', ', $cannotDelete) . " (em uso).";
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Duplicação em lote
     */
    private function bulkDuplicate($questions)
    {
        $duplicated = 0;
        
        DB::transaction(function () use ($questions, &$duplicated) {
            foreach ($questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->title = $question->title . ' (Cópia)';
                $newQuestion->created_by = Auth::id();
                $newQuestion->save();
                
                // Duplicar opções
                foreach ($question->options as $option) {
                    $newOption = $option->replicate();
                    $newOption->question_id = $newQuestion->id;
                    $newOption->save();
                }
                
                $duplicated++;
            }
        });
        
        return back()->with('success', "{$duplicated} questão(ões) duplicada(s) com sucesso!");
    }
    
    /**
     * Exportação em lote
     */
    private function bulkExport($questions)
    {
        $data = [
            'export_date' => now()->toISOString(),
            'total_questions' => $questions->count(),
            'questions' => $questions->load(['options', 'subject'])->map(function ($question) {
                return [
                    'title' => $question->title,
                    'content' => $question->content,
                    'type' => $question->type,
                    'difficulty' => $question->difficulty,
                    'points' => $question->points,
                    'subject' => $question->subject->name,
                    'explanation' => $question->explanation,
                    'image_description' => $question->image_description,
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
        
        $filename = 'questoes_export_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Alterar disciplina em lote
     */
    private function bulkChangeSubject($questions, $subjectId)
    {
        if (!$subjectId) {
            return back()->with('error', 'Disciplina de destino é obrigatória.');
        }
        
        $updated = 0;
        foreach ($questions as $question) {
            $question->update(['subject_id' => $subjectId]);
            $updated++;
        }
        
        $subject = Subject::find($subjectId);
        return back()->with('success', "{$updated} questão(ões) movida(s) para {$subject->name}!");
    }
    
    /**
     * Alterar dificuldade em lote
     */
    private function bulkChangeDifficulty($questions, $difficulty)
    {
        if (!$difficulty) {
            return back()->with('error', 'Dificuldade de destino é obrigatória.');
        }
        
        $updated = 0;
        foreach ($questions as $question) {
            $question->update(['difficulty' => $difficulty]);
            $updated++;
        }
        
        $difficultyLabels = [
            'easy' => 'Fácil',
            'medium' => 'Médio',
            'hard' => 'Difícil'
        ];
        
        return back()->with('success', "{$updated} questão(ões) alterada(s) para {$difficultyLabels[$difficulty]}!");
    }
    
    /**
     * Importar questões
     */
    public function import(Request $request)
    {
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem importar questões.');
        }
        
        $request->validate([
            'import_file' => 'required|file|mimes:json,csv',
            'default_subject_id' => 'required|exists:subjects,id'
        ]);
        
        $file = $request->file('import_file');
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'json') {
            return $this->importFromJson($file, $request->default_subject_id);
        } else {
            return $this->importFromCsv($file, $request->default_subject_id);
        }
    }
    
    /**
     * Importar de JSON
     */
    private function importFromJson($file, $defaultSubjectId)
    {
        $content = file_get_contents($file->path());
        $data = json_decode($content, true);
        
        if (!$data || !isset($data['questions'])) {
            return back()->with('error', 'Arquivo JSON inválido.');
        }
        
        $imported = 0;
        $errors = [];
        
        DB::transaction(function () use ($data, $defaultSubjectId, &$imported, &$errors) {
            foreach ($data['questions'] as $questionData) {
                try {
                    // Encontrar ou usar disciplina padrão
                    $subject = Subject::where('name', $questionData['subject'] ?? '')->first();
                    $subjectId = $subject ? $subject->id : $defaultSubjectId;
                    
                    $question = Question::create([
                        'title' => $questionData['title'],
                        'content' => $questionData['content'],
                        'type' => $questionData['type'] ?? 'multiple_choice',
                        'difficulty' => $questionData['difficulty'] ?? 'medium',
                        'points' => $questionData['points'] ?? 1,
                        'subject_id' => $subjectId,
                        'explanation' => $questionData['explanation'] ?? null,
                        'created_by' => Auth::id(),
                    ]);
                    
                    // Importar opções se existirem
                    if (isset($questionData['options'])) {
                        foreach ($questionData['options'] as $optionData) {
                            $question->options()->create([
                                'content' => $optionData['content'],
                                'is_correct' => $optionData['is_correct'] ?? false,
                                'order' => $optionData['order'] ?? 1,
                            ]);
                        }
                    }
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Erro na questão '{$questionData['title']}': " . $e->getMessage();
                }
            }
        });
        
        $message = "{$imported} questão(ões) importada(s) com sucesso!";
        if (!empty($errors)) {
            $message .= " Erros: " . implode('; ', array_slice($errors, 0, 3));
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Importar de CSV
     */
    private function importFromCsv($file, $defaultSubjectId)
    {
        $content = file_get_contents($file->path());
        $lines = explode("\n", $content);
        $header = str_getcsv(array_shift($lines));
        
        $imported = 0;
        $errors = [];
        
        DB::transaction(function () use ($lines, $header, $defaultSubjectId, &$imported, &$errors) {
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                $data = str_getcsv($line);
                $questionData = array_combine($header, $data);
                
                try {
                    $question = Question::create([
                        'title' => $questionData['title'] ?? 'Questão Importada',
                        'content' => $questionData['content'] ?? '',
                        'type' => $questionData['type'] ?? 'multiple_choice',
                        'difficulty' => $questionData['difficulty'] ?? 'medium',
                        'points' => $questionData['points'] ?? 1,
                        'subject_id' => $defaultSubjectId,
                        'created_by' => Auth::id(),
                    ]);
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Erro na linha {$imported}: " . $e->getMessage();
                }
            }
        });
        
        $message = "{$imported} questão(ões) importada(s) com sucesso!";
        if (!empty($errors)) {
            $message .= " Erros: " . implode('; ', array_slice($errors, 0, 3));
        }
        
        return back()->with('success', $message);
    }
}