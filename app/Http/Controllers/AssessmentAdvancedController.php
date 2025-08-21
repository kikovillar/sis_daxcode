<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\StudentAssessment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AssessmentAdvancedController extends Controller
{
    /**
     * Configurações avançadas de avaliação
     */
    public function advancedSettings(Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $assessment->load(['questions', 'classes']);
        
        return view('assessments.advanced-settings', compact('assessment'));
    }
    
    /**
     * Atualizar configurações avançadas
     */
    public function updateAdvancedSettings(Request $request, Assessment $assessment)
    {
        $this->authorize('update', $assessment);
        
        $validated = $request->validate([
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'require_webcam' => 'boolean',
            'prevent_copy_paste' => 'boolean',
            'time_limit_warning' => 'nullable|integer|min:1|max:60',
            'auto_submit' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'passing_score' => 'nullable|numeric|min:0|max:100',
            'certificate_template' => 'nullable|string',
            'feedback_enabled' => 'boolean',
            'question_feedback' => 'boolean',
            'navigation_mode' => 'in:free,sequential,locked',
            'browser_lockdown' => 'boolean',
        ]);
        
        // Atualizar configurações no JSON
        $settings = array_merge($assessment->settings ?? [], $validated);
        $assessment->update(['settings' => $settings]);
        
        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Configurações avançadas atualizadas com sucesso!');
    }
    
    /**
     * Preview da avaliação
     */
    public function preview(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $assessment->load(['questions.options', 'subject']);
        
        return view('assessments.preview', compact('assessment'));
    }
    
    /**
     * Análise de dificuldade automática
     */
    public function analyzeDifficulty(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $analysis = Cache::remember("assessment_difficulty_{$assessment->id}", 3600, function () use ($assessment) {
            $questions = $assessment->questions()->with('studentAnswers')->get();
            
            $difficultyAnalysis = [];
            foreach ($questions as $question) {
                $totalAnswers = $question->studentAnswers->count();
                $correctAnswers = $question->studentAnswers->where('is_correct', true)->count();
                $accuracy = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;
                
                // Classificar dificuldade baseada na taxa de acerto
                $actualDifficulty = 'medium';
                if ($accuracy >= 80) $actualDifficulty = 'easy';
                elseif ($accuracy <= 40) $actualDifficulty = 'hard';
                
                $difficultyAnalysis[] = [
                    'question' => $question,
                    'expected_difficulty' => $question->difficulty,
                    'actual_difficulty' => $actualDifficulty,
                    'accuracy_rate' => $accuracy,
                    'total_answers' => $totalAnswers,
                    'needs_review' => $question->difficulty !== $actualDifficulty && $totalAnswers >= 10
                ];
            }
            
            return $difficultyAnalysis;
        });
        
        return response()->json($analysis);
    }
    
    /**
     * Sugestões de melhoria
     */
    public function suggestions(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $suggestions = [];
        
        // Análise de questões
        $questionCount = $assessment->questions->count();
        if ($questionCount < 5) {
            $suggestions[] = [
                'type' => 'warning',
                'category' => 'Questões',
                'message' => 'Considere adicionar mais questões para uma avaliação mais robusta.',
                'action' => 'Adicionar questões',
                'priority' => 'medium'
            ];
        }
        
        // Análise de tempo
        $avgTimePerQuestion = $assessment->duration_minutes / max($questionCount, 1);
        if ($avgTimePerQuestion < 1) {
            $suggestions[] = [
                'type' => 'error',
                'category' => 'Tempo',
                'message' => 'Tempo muito curto por questão. Considere aumentar a duração.',
                'action' => 'Aumentar duração',
                'priority' => 'high'
            ];
        }
        
        // Análise de distribuição de dificuldade
        $difficulties = $assessment->questions->groupBy('difficulty');
        if ($difficulties->count() === 1) {
            $suggestions[] = [
                'type' => 'info',
                'category' => 'Dificuldade',
                'message' => 'Considere variar a dificuldade das questões para melhor avaliação.',
                'action' => 'Diversificar dificuldade',
                'priority' => 'low'
            ];
        }
        
        // Análise de tipos de questão
        $types = $assessment->questions->groupBy('type');
        if ($types->count() === 1 && $types->keys()->first() === 'multiple_choice') {
            $suggestions[] = [
                'type' => 'info',
                'category' => 'Tipos de Questão',
                'message' => 'Considere adicionar questões dissertativas para avaliação mais completa.',
                'action' => 'Adicionar questões dissertativas',
                'priority' => 'low'
            ];
        }
        
        return response()->json($suggestions);
    }
    
    /**
     * Gerar certificado
     */
    public function generateCertificate(StudentAssessment $studentAssessment)
    {
        $assessment = $studentAssessment->assessment;
        $student = $studentAssessment->student;
        
        // Verificar se passou
        $passingScore = $assessment->settings['passing_score'] ?? 60;
        $scorePercentage = ($studentAssessment->score / $assessment->max_score) * 100;
        
        if ($scorePercentage < $passingScore) {
            return response()->json(['error' => 'Nota insuficiente para certificado'], 400);
        }
        
        $certificateData = [
            'student_name' => $student->name,
            'assessment_title' => $assessment->title,
            'subject' => $assessment->subject->name,
            'score' => $studentAssessment->score,
            'max_score' => $assessment->max_score,
            'percentage' => round($scorePercentage, 1),
            'completion_date' => $studentAssessment->finished_at->format('d/m/Y'),
            'certificate_id' => 'CERT-' . $assessment->id . '-' . $student->id . '-' . time()
        ];
        
        return view('assessments.certificate', $certificateData);
    }
    
    /**
     * Backup de avaliação
     */
    public function backup(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $backup = [
            'assessment' => $assessment->toArray(),
            'questions' => $assessment->questions()->with('options')->get()->toArray(),
            'classes' => $assessment->classes->toArray(),
            'settings' => $assessment->settings,
            'backup_date' => now()->toISOString(),
            'version' => '1.0'
        ];
        
        $filename = 'backup_' . \Str::slug($assessment->title) . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($backup)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Restaurar avaliação do backup
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json'
        ]);
        
        $backupContent = file_get_contents($request->file('backup_file')->path());
        $backup = json_decode($backupContent, true);
        
        if (!$backup || !isset($backup['assessment'])) {
            return back()->with('error', 'Arquivo de backup inválido.');
        }
        
        DB::transaction(function () use ($backup) {
            // Criar nova avaliação
            $assessmentData = $backup['assessment'];
            unset($assessmentData['id'], $assessmentData['created_at'], $assessmentData['updated_at']);
            $assessmentData['title'] .= ' (Restaurada)';
            $assessmentData['status'] = 'draft';
            $assessmentData['teacher_id'] = Auth::id();
            
            $assessment = Assessment::create($assessmentData);
            
            // Restaurar questões (se existirem no sistema)
            foreach ($backup['questions'] as $questionData) {
                $existingQuestion = Question::where('title', $questionData['title'])
                    ->where('content', $questionData['content'])
                    ->first();
                
                if ($existingQuestion) {
                    $assessment->questions()->attach($existingQuestion->id, [
                        'order' => $questionData['pivot']['order'] ?? 1,
                        'points_override' => $questionData['pivot']['points_override'] ?? null
                    ]);
                }
            }
            
            return $assessment;
        });
        
        return redirect()->route('assessments.index')
            ->with('success', 'Avaliação restaurada com sucesso!');
    }
    
    /**
     * Comparar avaliações
     */
    public function compare(Request $request)
    {
        $request->validate([
            'assessment_ids' => 'required|array|min:2|max:3',
            'assessment_ids.*' => 'exists:assessments,id'
        ]);
        
        $assessments = Assessment::whereIn('id', $request->assessment_ids)
            ->with(['questions', 'studentAssessments', 'subject'])
            ->get();
        
        $comparison = [];
        foreach ($assessments as $assessment) {
            $stats = $assessment->getStats();
            $comparison[] = [
                'assessment' => $assessment,
                'stats' => $stats,
                'avg_time' => $this->calculateAverageTime($assessment),
                'difficulty_distribution' => $assessment->questions->groupBy('difficulty')->map->count(),
                'type_distribution' => $assessment->questions->groupBy('type')->map->count(),
            ];
        }
        
        return view('assessments.compare', compact('comparison'));
    }
    
    /**
     * Calcular tempo médio
     */
    private function calculateAverageTime(Assessment $assessment)
    {
        return $assessment->studentAssessments()
            ->where('status', 'completed')
            ->whereNotNull('finished_at')
            ->get()
            ->map(function ($attempt) {
                return $attempt->finished_at->diffInMinutes($attempt->started_at);
            })
            ->avg();
    }
}