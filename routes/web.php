<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentAdvancedController;
use App\Http\Controllers\AssessmentDashboardController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionBulkController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentAssessmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/simple', function () {
    return response('Sistema funcionando! Encoding OK.', 200)
        ->header('Content-Type', 'text/html; charset=utf-8');
});

Route::get('/test-assessment', function () {
    // Quick test for assessment functionality
    $user = \App\Models\User::where('role', 'aluno')->first();
    $assessment = \App\Models\Assessment::where('status', 'published')->first();
    
    if (!$user || !$assessment) {
        return response('❌ Dados de teste não encontrados. Execute: php artisan migrate --seed', 400);
    }
    
    $questions = $assessment->questions()->with('options')->get();
    
    return response("
        ✅ Sistema de Avaliação - Teste Rápido<br><br>
        👤 Usuário teste: {$user->name} ({$user->email})<br>
        📝 Avaliação: {$assessment->title}<br>
        ❓ Questões: {$questions->count()}<br>
        ⏱️ Duração: {$assessment->duration_minutes} minutos<br><br>
        
        <strong>Para testar:</strong><br>
        1. Faça login com: {$user->email} / password<br>
        2. Acesse: <a href='/student/assessments'>/student/assessments</a><br>
        3. Clique em 'Iniciar Prova'<br><br>
        
        <a href='/login'>🔐 Fazer Login</a> | 
        <a href='/student/assessments'>📝 Ver Avaliações</a>
    ", 200)->header('Content-Type', 'text/html; charset=utf-8');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Estatísticas gerais
    $stats = [
        'total_users' => \App\Models\User::count(),
        'admins' => \App\Models\User::where('role', 'admin')->count(),
        'teachers' => \App\Models\User::where('role', 'professor')->count(),
        'students' => \App\Models\User::where('role', 'aluno')->count(),
        'total_assessments' => \App\Models\Assessment::count(),
        'published_assessments' => \App\Models\Assessment::where('status', 'published')->count(),
        'draft_assessments' => \App\Models\Assessment::where('status', 'draft')->count(),
        'total_questions' => \App\Models\Question::count(),
        'total_attempts' => \App\Models\StudentAssessment::count(),
    ];

    // Dados para gráficos
    $monthlyData = [];
    $assessmentsBySubject = [];
    $recentActivity = [];

    // Dados mensais (últimos 6 meses)
    $months = [];
    $assessmentsData = [];
    $attemptsData = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $months[] = $date->format('M Y');
        
        $assessmentsData[] = \App\Models\Assessment::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
            
        $attemptsData[] = \App\Models\StudentAssessment::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
    }
    
    $monthlyData = [
        'labels' => $months,
        'assessments' => $assessmentsData,
        'attempts' => $attemptsData
    ];

    // Avaliações por disciplina
    $subjectStats = \App\Models\Subject::withCount('assessments')->get();
    $assessmentsBySubject = [
        'labels' => $subjectStats->pluck('name')->toArray(),
        'data' => $subjectStats->pluck('assessments_count')->toArray()
    ];

    // Atividade recente
    $recentActivity = \App\Models\Assessment::with(['teacher', 'subject'])
        ->latest()
        ->limit(5)
        ->get();

    return view('dashboard', compact('stats', 'monthlyData', 'assessmentsBySubject', 'recentActivity'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas de Avaliações
    Route::resource('assessments', AssessmentController::class);
    
    // Rotas de Disciplinas
    Route::resource('subjects', SubjectController::class);
    Route::get('/subjects/search/api', [SubjectController::class, 'search'])->name('subjects.search');
    
    // Rotas de Questões
    Route::resource('questions', QuestionController::class);
    Route::get('/questions/search/api', [QuestionController::class, 'search'])->name('questions.search');
    Route::post('/questions/bulk', [QuestionBulkController::class, 'bulkActions'])->name('questions.bulk');
    Route::post('/questions/import', [QuestionBulkController::class, 'import'])->name('questions.import');
    
    // Rotas de Turmas
    Route::resource('classes', ClassController::class);
    Route::get('/classes/search/api', [ClassController::class, 'search'])->name('classes.search');
    Route::post('/classes/{class}/add-student', [ClassController::class, 'addStudent'])->name('classes.add-student');
    Route::delete('/classes/{class}/students/{student}', [ClassController::class, 'removeStudent'])->name('classes.remove-student');
    
    // Gerenciamento avançado de alunos nas turmas
    Route::get('/classes/{class}/manage-students', [ClassController::class, 'manageStudents'])->name('classes.manage-students');
    Route::post('/classes/{class}/add-multiple-students', [ClassController::class, 'addMultipleStudents'])->name('classes.add-multiple-students');
    Route::post('/classes/{class}/remove-multiple-students', [ClassController::class, 'removeMultipleStudents'])->name('classes.remove-multiple-students');
    Route::post('/classes/{class}/transfer-student/{student}', [ClassController::class, 'transferStudent'])->name('classes.transfer-student');
    
    // Dashboard e Relatórios
    Route::get('/dashboard/assessments', [AssessmentDashboardController::class, 'index'])->name('assessments.dashboard');
    Route::get('/assessments/{assessment}/report', [AssessmentDashboardController::class, 'assessmentReport'])->name('assessments.report');
    Route::post('/assessments/{assessment}/duplicate', [AssessmentDashboardController::class, 'duplicate'])->name('assessments.duplicate');
    Route::get('/assessments/{assessment}/export', [AssessmentDashboardController::class, 'export'])->name('assessments.export');
    
    // Funcionalidades Avançadas
    Route::get('/assessments/{assessment}/advanced', [AssessmentAdvancedController::class, 'advancedSettings'])->name('assessments.advanced');
    Route::put('/assessments/{assessment}/advanced', [AssessmentAdvancedController::class, 'updateAdvancedSettings'])->name('assessments.advanced.update');
    Route::get('/assessments/{assessment}/preview', [AssessmentAdvancedController::class, 'preview'])->name('assessments.preview');
    Route::get('/assessments/{assessment}/analyze', [AssessmentAdvancedController::class, 'analyzeDifficulty'])->name('assessments.analyze');
    Route::get('/assessments/{assessment}/suggestions', [AssessmentAdvancedController::class, 'suggestions'])->name('assessments.suggestions');
    Route::post('/assessments/restore', [AssessmentAdvancedController::class, 'restore'])->name('assessments.restore');
    Route::get('/assessments/{assessment}/backup', [AssessmentAdvancedController::class, 'backup'])->name('assessments.backup');
    
    // Rotas para Alunos
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/assessments', [StudentAssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/{assessment}/start', [StudentAssessmentController::class, 'start'])->name('assessments.start');
        Route::get('/assessments/{studentAssessment}/take', [StudentAssessmentController::class, 'take'])->name('assessments.take');
        Route::post('/assessments/{studentAssessment}/save-answer', [StudentAssessmentController::class, 'saveAnswer'])->name('assessments.save-answer');
        Route::post('/assessments/{studentAssessment}/submit', [StudentAssessmentController::class, 'submit'])->name('assessments.submit');
        Route::get('/assessments/{studentAssessment}/result', [StudentAssessmentController::class, 'result'])->name('assessments.result');
    });
    
    
    // Rotas específicas para professores
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::post('/assessments/{assessment}/publish', [AssessmentController::class, 'publish'])->name('assessment.publish');
        Route::post('/assessments/{assessment}/close', [AssessmentController::class, 'close'])->name('assessment.close');
        Route::post('/assessments/{assessment}/add-questions', [AssessmentController::class, 'addQuestions'])->name('assessments.add-questions');
        Route::delete('/assessments/{assessment}/questions/{question}', [AssessmentController::class, 'removeQuestion'])->name('assessment.remove-question');
        Route::patch('/assessments/{assessment}/questions/reorder', [AssessmentController::class, 'reorderQuestions'])->name('assessment.reorder-questions');
    });
    
    // Rotas para gerenciamento de questões em avaliações
    Route::get('/assessment-questions', [\App\Http\Controllers\AssessmentQuestionController::class, 'index'])->name('assessment-questions.index');
    Route::get('/assessments/{assessment}/manage-questions', [\App\Http\Controllers\AssessmentQuestionController::class, 'manage'])->name('assessment-questions.manage');
    Route::post('/assessments/{assessment}/add-question', [\App\Http\Controllers\AssessmentQuestionController::class, 'addQuestion'])->name('assessment-questions.add');
    Route::delete('/assessments/{assessment}/questions/{question}', [\App\Http\Controllers\AssessmentQuestionController::class, 'removeQuestion'])->name('assessment-questions.remove');
    
    // Rotas de administração
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        // Gerenciamento de usuários
        Route::resource('users', UserManagementController::class);
        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/users/{user}/export', [UserManagementController::class, 'exportToExcel'])->name('users.export');
        
        // Rotas de relatórios
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('index');
            Route::get('/performance', [ReportsController::class, 'performance'])->name('performance');
            Route::get('/assessments', [ReportsController::class, 'assessments'])->name('assessments');
            Route::get('/students', [ReportsController::class, 'students'])->name('students');
            Route::get('/export/{type}', [ReportsController::class, 'export'])->name('export');
        });
        
        // Gerenciamento de fotos de usuários
        Route::post('/users/{user}/photo', [\App\Http\Controllers\UserPhotoController::class, 'upload'])->name('users.photo.upload');
        Route::delete('/users/{user}/photo', [\App\Http\Controllers\UserPhotoController::class, 'remove'])->name('users.photo.remove');
        
        // Configurações do sistema
        Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SystemSettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/reset-logo', [SystemSettingsController::class, 'resetLogo'])->name('settings.reset-logo');
        Route::get('/settings/preview', [SystemSettingsController::class, 'preview'])->name('settings.preview');
        Route::get('/settings/export', [SystemSettingsController::class, 'export'])->name('settings.export');
        Route::post('/settings/import', [SystemSettingsController::class, 'import'])->name('settings.import');
        
        // Relatórios
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/users', [ReportsController::class, 'users'])->name('reports.users');
        Route::get('/reports/assessments', [ReportsController::class, 'assessments'])->name('reports.assessments');
        Route::get('/reports/performance', [ReportsController::class, 'performance'])->name('reports.performance');
        Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
    });
});

require __DIR__.'/auth.php';


