<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assessment;
use App\Models\StudentAssessment;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Dashboard de relatórios gerais
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        // Estatísticas gerais
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'teachers' => User::where('role', 'professor')->count(),
            'students' => User::where('role', 'aluno')->count(),
            'total_assessments' => Assessment::count(),
            'draft_assessments' => Assessment::where('status', 'draft')->count(),
            'published_assessments' => Assessment::where('status', 'published')->count(),
            'closed_assessments' => Assessment::where('status', 'closed')->count(),
            'total_questions' => Question::count(),
            'total_attempts' => StudentAssessment::count(),
            'completed_attempts' => StudentAssessment::where('status', 'completed')->count(),
        ];

        // Dados mensais (últimos 6 meses)
        $months = [];
        $assessmentsData = [];
        $attemptsData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $assessmentsData[] = Assessment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $attemptsData[] = StudentAssessment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        $monthlyData = [
            'labels' => $months,
            'assessments' => $assessmentsData,
            'attempts' => $attemptsData
        ];

        // Distribuição de notas
        $gradesDistribution = [
            StudentAssessment::where('score', '>=', 0)->where('score', '<', 2)->count(),
            StudentAssessment::where('score', '>=', 2)->where('score', '<', 4)->count(),
            StudentAssessment::where('score', '>=', 4)->where('score', '<', 6)->count(),
            StudentAssessment::where('score', '>=', 6)->where('score', '<', 8)->count(),
            StudentAssessment::where('score', '>=', 8)->where('score', '<=', 10)->count(),
        ];

        // Top 5 avaliações mais realizadas
        $topAssessments = Assessment::withCount('studentAssessments')
            ->with('subject')
            ->orderBy('student_assessments_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($assessment) {
                $assessment->attempts_count = $assessment->student_assessments_count;
                return $assessment;
            });

        // Top 5 professores mais ativos
        $topTeachers = User::where('role', 'professor')
            ->withCount('assessments')
            ->orderBy('assessments_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.reports.dashboard', compact(
            'stats', 
            'monthlyData', 
            'gradesDistribution', 
            'topAssessments', 
            'topTeachers'
        ));
    }

    /**
     * Relatório de performance detalhado
     */
    public function performance(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $period = $request->get('period', 30);
        $subjectId = $request->get('subject_id');
        $teacherId = $request->get('teacher_id');

        // Filtros
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'professor')->orderBy('name')->get();

        // Query base para tentativas
        $attemptsQuery = StudentAssessment::with(['assessment.subject', 'user'])
            ->where('created_at', '>=', now()->subDays($period));

        if ($subjectId) {
            $attemptsQuery->whereHas('assessment.subject', function ($query) use ($subjectId) {
                $query->where('id', $subjectId);
            });
        }

        if ($teacherId) {
            $attemptsQuery->whereHas('assessment', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            });
        }

        // Estatísticas de performance
        $attempts = $attemptsQuery->get();
        $performanceStats = [
            'average_score' => $attempts->avg('score') ?? 0,
            'completion_rate' => $attempts->where('status', 'completed')->count() > 0 
                ? round(($attempts->where('status', 'completed')->count() / $attempts->count()) * 100) 
                : 0,
            'total_attempts' => $attempts->count(),
            'average_time' => $attempts->where('completed_at', '!=', null)->avg(function ($attempt) {
                return $attempt->completed_at ? $attempt->completed_at->diffInMinutes($attempt->started_at) : 0;
            }) ?? 0
        ];

        // Performance por avaliação
        $assessmentPerformance = [
            'labels' => [],
            'scores' => []
        ];

        $assessmentScores = $attempts->groupBy('assessment_id')->map(function ($group) {
            return [
                'title' => $group->first()->assessment->title,
                'average' => $group->avg('score')
            ];
        })->sortByDesc('average')->take(10);

        foreach ($assessmentScores as $score) {
            $assessmentPerformance['labels'][] = Str::limit($score['title'], 20);
            $assessmentPerformance['scores'][] = round($score['average'], 1);
        }

        // Distribuição de notas
        $gradeDistribution = [
            $attempts->where('score', '>=', 0)->where('score', '<', 2)->count(),
            $attempts->where('score', '>=', 2)->where('score', '<', 4)->count(),
            $attempts->where('score', '>=', 4)->where('score', '<', 6)->count(),
            $attempts->where('score', '>=', 6)->where('score', '<', 8)->count(),
            $attempts->where('score', '>=', 8)->where('score', '<=', 10)->count(),
        ];

        // Evolução temporal
        $evolutionData = [
            'labels' => [],
            'scores' => [],
            'completion' => []
        ];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayAttempts = $attempts->filter(function ($attempt) use ($date) {
                return $attempt->created_at->isSameDay($date);
            });

            $evolutionData['labels'][] = $date->format('d/m');
            $evolutionData['scores'][] = $dayAttempts->avg('score') ?? 0;
            $evolutionData['completion'][] = $dayAttempts->count() > 0 
                ? round(($dayAttempts->where('status', 'completed')->count() / $dayAttempts->count()) * 100)
                : 0;
        }

        // Ranking de alunos com paginação
        $studentsQuery = User::where('role', 'aluno')
            ->with(['studentAssessments' => function ($query) use ($period, $subjectId, $teacherId) {
                $query->where('created_at', '>=', now()->subDays($period));
                if ($subjectId) {
                    $query->whereHas('assessment.subject', function ($q) use ($subjectId) {
                        $q->where('id', $subjectId);
                    });
                }
                if ($teacherId) {
                    $query->whereHas('assessment', function ($q) use ($teacherId) {
                        $q->where('teacher_id', $teacherId);
                    });
                }
            }])
            ->whereHas('studentAssessments', function ($query) use ($period, $subjectId, $teacherId) {
                $query->where('created_at', '>=', now()->subDays($period));
                if ($subjectId) {
                    $query->whereHas('assessment.subject', function ($q) use ($subjectId) {
                        $q->where('id', $subjectId);
                    });
                }
                if ($teacherId) {
                    $query->whereHas('assessment', function ($q) use ($teacherId) {
                        $q->where('teacher_id', $teacherId);
                    });
                }
            });

        $topStudents = $studentsQuery->paginate(20)->through(function ($student) {
            $assessments = $student->studentAssessments;
            $student->average_score = $assessments->avg('score');
            $student->total_assessments = $assessments->count();
            $student->completion_rate = $assessments->count() > 0 
                ? round(($assessments->where('status', 'completed')->count() / $assessments->count()) * 100)
                : 0;
            return $student;
        });

        return view('admin.reports.performance', compact(
            'subjects',
            'teachers', 
            'performanceStats',
            'assessmentPerformance',
            'gradeDistribution',
            'evolutionData',
            'topStudents'
        ));
    }
        $topAssessments = Assessment::withCount('studentAssessments')
            ->orderBy('student_assessments_count', 'desc')
            ->limit(5)
            ->get();

        // Média de notas por avaliação
        $averageScores = StudentAssessment::select('assessment_id')
            ->selectRaw('AVG(score) as average_score')
            ->with('assessment:id,title')
            ->where('status', 'completed')
            ->groupBy('assessment_id')
            ->orderBy('average_score', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'stats', 
            'usersByMonth', 
            'assessmentsByStatus', 
            'topAssessments', 
            'averageScores'
        ));
    }

    /**
     * Relatório de usuários
     */
    public function users(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $query = User::query();

        // Filtros
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reports.users', compact('users'));
    }

    /**
     * Relatório de avaliações
     */
    public function assessments(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $query = Assessment::withCount(['studentAssessments', 'questions']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('teacher_id')) {
            $query->where('created_by', $request->teacher_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $assessments = $query->with('creator:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $teachers = User::where('role', 'teacher')->select('id', 'name')->get();

        return view('admin.reports.assessments', compact('assessments', 'teachers'));
    }

    /**
     * Relatório de desempenho
     */
    public function performance(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $query = StudentAssessment::with(['student:id,name', 'assessment:id,title'])
            ->where('status', 'completed');

        // Filtros
        if ($request->filled('assessment_id')) {
            $query->where('assessment_id', $request->assessment_id);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('score_min')) {
            $query->where('score', '>=', $request->score_min);
        }

        if ($request->filled('score_max')) {
            $query->where('score', '<=', $request->score_max);
        }

        $attempts = $query->orderBy('completed_at', 'desc')->paginate(20);

        $assessments = Assessment::select('id', 'title')->get();
        $students = User::where('role', 'student')->select('id', 'name')->get();

        return view('admin.reports.performance', compact('attempts', 'assessments', 'students'));
    }

    /**
     * Exportar relatório
     */
    public function export(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $type = $request->get('type', 'users');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'users':
                return $this->exportUsers($format);
            case 'assessments':
                return $this->exportAssessments($format);
            case 'performance':
                return $this->exportPerformance($format);
            default:
                return back()->with('error', 'Tipo de relatório inválido.');
        }
    }

    private function exportUsers($format)
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')->get();
        
        if ($format === 'csv') {
            $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Nome', 'Email', 'Papel', 'Data de Criação']);
                
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role,
                        $user->created_at->format('d/m/Y H:i:s')
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Formato não suportado.');
    }

    private function exportAssessments($format)
    {
        $assessments = Assessment::with('creator:id,name')
            ->withCount(['studentAssessments', 'questions'])
            ->get();
        
        if ($format === 'csv') {
            $filename = 'avaliacoes_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($assessments) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Título', 'Criador', 'Status', 'Questões', 'Tentativas', 'Data de Criação']);
                
                foreach ($assessments as $assessment) {
                    fputcsv($file, [
                        $assessment->id,
                        $assessment->title,
                        $assessment->creator->name ?? 'N/A',
                        $assessment->status,
                        $assessment->questions_count,
                        $assessment->student_assessments_count,
                        $assessment->created_at->format('d/m/Y H:i:s')
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Formato não suportado.');
    }

    private function exportPerformance($format)
    {
        $attempts = StudentAssessment::with(['student:id,name', 'assessment:id,title'])
            ->where('status', 'completed')
            ->get();
        
        if ($format === 'csv') {
            $filename = 'desempenho_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($attempts) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Aluno', 'Avaliação', 'Nota', 'Tempo Gasto', 'Data de Conclusão']);
                
                foreach ($attempts as $attempt) {
                    fputcsv($file, [
                        $attempt->id,
                        $attempt->student->name ?? 'N/A',
                        $attempt->assessment->title ?? 'N/A',
                        $attempt->score,
                        $attempt->time_spent ?? 'N/A',
                        $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i:s') : 'N/A'
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Formato não suportado.');
    }
}