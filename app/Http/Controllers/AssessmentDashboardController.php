<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\StudentAssessment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentDashboardController extends Controller
{
    /**
     * Dashboard principal para professores
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        // Filtrar por professor se não for admin
        $assessmentQuery = Assessment::query();
        if ($user->isTeacher()) {
            $assessmentQuery->where('teacher_id', $user->id);
        }
        
        // Estatísticas gerais
        $stats = [
            'total_assessments' => $assessmentQuery->count(),
            'published_assessments' => $assessmentQuery->where('status', 'published')->count(),
            'draft_assessments' => $assessmentQuery->where('status', 'draft')->count(),
            'closed_assessments' => $assessmentQuery->where('status', 'closed')->count(),
            'total_attempts' => StudentAssessment::whereHas('assessment', function($q) use ($user) {
                if ($user->isTeacher()) {
                    $q->where('teacher_id', $user->id);
                }
            })->count(),
            'completed_attempts' => StudentAssessment::whereHas('assessment', function($q) use ($user) {
                if ($user->isTeacher()) {
                    $q->where('teacher_id', $user->id);
                }
            })->where('status', 'completed')->count(),
        ];
        
        // Avaliações recentes
        $recentAssessments = $assessmentQuery->with(['subject', 'studentAssessments'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Estatísticas por mês (últimos 6 meses)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $monthlyStats[] = [
                'month' => $date->format('M/Y'),
                'assessments' => $assessmentQuery->whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'attempts' => StudentAssessment::whereHas('assessment', function($q) use ($user) {
                    if ($user->isTeacher()) {
                        $q->where('teacher_id', $user->id);
                    }
                })->whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        }
        
        // Top 5 avaliações por tentativas
        $topAssessments = $assessmentQuery->withCount('studentAssessments')
            ->orderBy('student_assessments_count', 'desc')
            ->limit(5)
            ->get();
        
        // Estatísticas por disciplina
        $subjectStats = Subject::withCount(['assessments' => function($q) use ($user) {
                if ($user->isTeacher()) {
                    $q->where('teacher_id', $user->id);
                }
            }])
            ->having('assessments_count', '>', 0)
            ->orderBy('assessments_count', 'desc')
            ->get();
        
        // Questões mais utilizadas
        $topQuestions = Question::select([
                'questions.id',
                'questions.title', 
                'questions.content', 
                'questions.type', 
                'questions.difficulty', 
                'questions.points', 
                'questions.subject_id', 
                'questions.explanation', 
                'questions.created_by', 
                'questions.image_path', 
                'questions.image_description', 
                'questions.created_at', 
                'questions.updated_at',
                DB::raw('COUNT(assessment_questions.assessment_id) as usage_count')
            ])
            ->join('assessment_questions', 'questions.id', '=', 'assessment_questions.question_id')
            ->join('assessments', 'assessment_questions.assessment_id', '=', 'assessments.id')
            ->when($user->isTeacher(), function($q) use ($user) {
                $q->where('assessments.teacher_id', $user->id);
            })
            ->with('subject')
            ->groupBy([
                'questions.id',
                'questions.title', 
                'questions.content', 
                'questions.type', 
                'questions.difficulty', 
                'questions.points', 
                'questions.subject_id', 
                'questions.explanation', 
                'questions.created_by', 
                'questions.image_path', 
                'questions.image_description', 
                'questions.created_at', 
                'questions.updated_at'
            ])
            ->orderBy('usage_count', 'DESC')
            ->limit(5)
            ->get();
        
        return view('assessments.dashboard', compact(
            'stats', 
            'recentAssessments', 
            'monthlyStats', 
            'topAssessments', 
            'subjectStats',
            'topQuestions'
        ));
    }
    
    /**
     * Relatório detalhado de uma avaliação
     */
    public function assessmentReport(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $assessment->load(['subject', 'questions.options', 'studentAssessments.student', 'classes']);
        
        // Estatísticas detalhadas
        $attempts = $assessment->studentAssessments();
        $completedAttempts = $attempts->where('status', 'completed');
        
        $stats = [
            'total_attempts' => $attempts->count(),
            'completed_attempts' => $completedAttempts->count(),
            'in_progress_attempts' => $attempts->where('status', 'in_progress')->count(),
            'average_score' => $completedAttempts->avg('score') ?? 0,
            'highest_score' => $completedAttempts->max('score') ?? 0,
            'lowest_score' => $completedAttempts->min('score') ?? 0,
            'median_score' => $this->calculateMedian($completedAttempts->pluck('score')->toArray()),
            'pass_rate' => $completedAttempts->count() > 0 ? 
                ($completedAttempts->where('score', '>=', $assessment->max_score * 0.6)->count() / $completedAttempts->count()) * 100 : 0,
        ];
        
        // Distribuição de notas
        $scoreDistribution = $this->getScoreDistribution($completedAttempts->pluck('score')->toArray(), $assessment->max_score);
        
        // Análise por questão
        $questionAnalysis = [];
        foreach ($assessment->questions as $question) {
            $questionStats = $this->analyzeQuestion($question, $assessment);
            $questionAnalysis[] = $questionStats;
        }
        
        // Tempo médio de conclusão
        $avgDuration = $completedAttempts->whereNotNull('finished_at')
            ->get()
            ->map(function($attempt) {
                return $attempt->finished_at->diffInMinutes($attempt->started_at);
            })
            ->avg();
        
        return view('assessments.report', compact(
            'assessment', 
            'stats', 
            'scoreDistribution', 
            'questionAnalysis',
            'avgDuration'
        ));
    }
    
    /**
     * Duplicar avaliação
     */
    public function duplicate(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        if (!Auth::user()->isTeacher() && !Auth::user()->isAdmin()) {
            abort(403, 'Apenas professores podem duplicar avaliações.');
        }
        
        DB::transaction(function () use ($assessment) {
            // Criar nova avaliação
            $newAssessment = $assessment->replicate();
            $newAssessment->title = $assessment->title . ' (Cópia)';
            $newAssessment->status = 'draft';
            $newAssessment->teacher_id = Auth::id();
            $newAssessment->opens_at = Carbon::now()->addDay();
            $newAssessment->closes_at = Carbon::now()->addWeek();
            $newAssessment->save();
            
            // Copiar questões
            foreach ($assessment->questions as $question) {
                $newAssessment->questions()->attach($question->id, [
                    'order' => $question->pivot->order,
                    'points_override' => $question->pivot->points_override,
                ]);
            }
            
            // Copiar turmas (apenas as que o professor tem acesso)
            $userClasses = \App\Models\ClassModel::where('teacher_id', Auth::id())->pluck('id');
            $classesToCopy = $assessment->classes()->whereIn('classes.id', $userClasses)->pluck('classes.id');
            $newAssessment->classes()->attach($classesToCopy);
            
            return $newAssessment;
        });
        
        return redirect()->route('assessments.index')
            ->with('success', 'Avaliação duplicada com sucesso! Você pode editá-la antes de publicar.');
    }
    
    /**
     * Exportar avaliação
     */
    public function export(Assessment $assessment)
    {
        $this->authorize('view', $assessment);
        
        $assessment->load(['subject', 'questions.options', 'classes']);
        
        $data = [
            'assessment' => [
                'title' => $assessment->title,
                'description' => $assessment->description,
                'duration_minutes' => $assessment->duration_minutes,
                'max_score' => $assessment->max_score,
                'subject' => $assessment->subject->name,
                'created_at' => $assessment->created_at->toISOString(),
            ],
            'questions' => $assessment->questions->map(function ($question) {
                return [
                    'title' => $question->title,
                    'content' => $question->content,
                    'type' => $question->type,
                    'difficulty' => $question->difficulty,
                    'points' => $question->pivot->points_override ?? $question->points,
                    'order' => $question->pivot->order,
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
        
        $filename = 'avaliacao_' . \Str::slug($assessment->title) . '_' . date('Y-m-d') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Calcular mediana
     */
    private function calculateMedian(array $scores)
    {
        if (empty($scores)) return 0;
        
        sort($scores);
        $count = count($scores);
        $middle = floor($count / 2);
        
        if ($count % 2) {
            return $scores[$middle];
        } else {
            return ($scores[$middle - 1] + $scores[$middle]) / 2;
        }
    }
    
    /**
     * Distribuição de notas
     */
    private function getScoreDistribution(array $scores, float $maxScore)
    {
        $ranges = [
            '0-20%' => 0,
            '21-40%' => 0,
            '41-60%' => 0,
            '61-80%' => 0,
            '81-100%' => 0,
        ];
        
        foreach ($scores as $score) {
            $percentage = ($score / $maxScore) * 100;
            
            if ($percentage <= 20) $ranges['0-20%']++;
            elseif ($percentage <= 40) $ranges['21-40%']++;
            elseif ($percentage <= 60) $ranges['41-60%']++;
            elseif ($percentage <= 80) $ranges['61-80%']++;
            else $ranges['81-100%']++;
        }
        
        return $ranges;
    }
    
    /**
     * Analisar questão específica
     */
    private function analyzeQuestion($question, $assessment)
    {
        $answers = \App\Models\StudentAnswer::where('question_id', $question->id)
            ->whereHas('studentAssessment', function($q) use ($assessment) {
                $q->where('assessment_id', $assessment->id)
                  ->where('status', 'completed');
            })
            ->get();
        
        $totalAnswers = $answers->count();
        $correctAnswers = $answers->where('is_correct', true)->count();
        
        return [
            'question' => $question,
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'accuracy_rate' => $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0,
            'avg_points' => $answers->avg('points_earned') ?? 0,
        ];
    }
}