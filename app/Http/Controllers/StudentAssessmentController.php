<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\StudentAssessment;
use App\Models\StudentAnswer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentAssessmentController extends Controller
{
    /**
     * Lista de avaliações disponíveis para o aluno
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Acesso restrito a alunos.');
        }
        
        // Buscar avaliações das turmas do aluno
        $availableAssessments = Assessment::whereHas('classes.students', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->where('status', 'published')
        ->where('opens_at', '<=', now())
        ->where('closes_at', '>=', now())
        ->with(['subject', 'studentAssessments' => function($query) use ($user) {
            $query->where('student_id', $user->id);
        }])
        ->orderBy('closes_at', 'asc')
        ->get();
        
        // Buscar avaliações concluídas
        $completedAssessments = Assessment::whereHas('studentAssessments', function($query) use ($user) {
            $query->where('student_id', $user->id)
                  ->where('status', 'completed');
        })
        ->with(['subject', 'studentAssessments' => function($query) use ($user) {
            $query->where('student_id', $user->id);
        }])
        ->orderBy('updated_at', 'desc')
        ->get();
        
        return view('student.assessments.index', compact('availableAssessments', 'completedAssessments'));
    }
    
    /**
     * Iniciar uma avaliação
     */
    public function start(Assessment $assessment)
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Acesso restrito a alunos.');
        }
        
        // Verificar se o aluno pode fazer esta avaliação
        if (!$this->canTakeAssessment($user, $assessment)) {
            return redirect()->route('student.assessments.index')
                ->with('error', 'Você não pode fazer esta avaliação.');
        }
        
        // Verificar se já existe uma tentativa em andamento
        $existingAttempt = StudentAssessment::where('student_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->where('status', 'in_progress')
            ->first();
        
        if ($existingAttempt) {
            return redirect()->route('student.assessments.take', $existingAttempt);
        }
        
        // Verificar limite de tentativas
        $settings = $assessment->settings ?? [];
        $maxAttempts = $settings['max_attempts'] ?? 1;
        $currentAttempts = StudentAssessment::where('student_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->count();
        
        if ($currentAttempts >= $maxAttempts) {
            return redirect()->route('student.assessments.index')
                ->with('error', 'Você já atingiu o limite de tentativas para esta avaliação.');
        }
        
        // Criar nova tentativa
        $studentAssessment = StudentAssessment::create([
            'student_id' => $user->id,
            'assessment_id' => $assessment->id,
            'started_at' => now(),
            'status' => 'in_progress',
            'score' => 0,
        ]);
        
        return redirect()->route('student.assessments.take', $studentAssessment);
    }
    
    /**
     * Realizar a avaliação
     */
    public function take(StudentAssessment $studentAssessment)
    {
        $user = Auth::user();
        
        // Verificar permissões
        if ($studentAssessment->student_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }
        
        if ($studentAssessment->status !== 'in_progress') {
            return redirect()->route('student.assessments.index')
                ->with('error', 'Esta avaliação já foi concluída.');
        }
        
        $assessment = $studentAssessment->assessment;
        $settings = $assessment->settings ?? [];
        
        // Verificar se ainda está no prazo
        if (now() > $assessment->closes_at) {
            $studentAssessment->update([
                'status' => 'completed',
                'finished_at' => now()
            ]);
            
            return redirect()->route('student.assessments.index')
                ->with('error', 'O prazo para esta avaliação expirou.');
        }
        
        // Carregar questões com ordem correta
        $questions = $assessment->questions()
            ->with('options')
            ->orderBy('assessment_questions.order', 'asc')
            ->get();
        
        // Embaralhar questões se configurado
        if ($settings['shuffle_questions'] ?? false) {
            $questions = $questions->shuffle();
        }
        
        // Embaralhar opções se configurado
        if ($settings['shuffle_options'] ?? false) {
            $questions->each(function($question) {
                if ($question->options->count() > 0) {
                    $question->setRelation('options', $question->options->shuffle());
                }
            });
        }
        
        // Carregar respostas já dadas
        $existingAnswers = StudentAnswer::where('student_assessment_id', $studentAssessment->id)
            ->pluck('answer_text', 'question_id')
            ->toArray();
        
        // Calcular tempo restante
        $timeLimit = $assessment->duration_minutes;
        $timeElapsed = $studentAssessment->started_at->diffInMinutes(now());
        $timeRemaining = max(0, $timeLimit - $timeElapsed);
        
        return view('student.assessments.take', compact(
            'studentAssessment', 
            'assessment', 
            'questions', 
            'existingAnswers', 
            'timeRemaining',
            'settings'
        ));
    }
    
    /**
     * Salvar resposta
     */
    public function saveAnswer(Request $request, StudentAssessment $studentAssessment)
    {
        $user = Auth::user();
        
        if ($studentAssessment->student_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }
        
        if ($studentAssessment->status !== 'in_progress') {
            return response()->json(['error' => 'Avaliação já concluída'], 400);
        }
        
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
        ]);
        
        $question = Question::findOrFail($validated['question_id']);
        
        // Verificar se a questão pertence à avaliação
        if (!$studentAssessment->assessment->questions()->where('questions.id', $question->id)->exists()) {
            return response()->json(['error' => 'Questão inválida'], 400);
        }
        
        // Salvar ou atualizar resposta
        $answer = StudentAnswer::updateOrCreate(
            [
                'student_assessment_id' => $studentAssessment->id,
                'question_id' => $question->id,
            ],
            [
                'answer_text' => $validated['answer'],
                'is_correct' => $this->checkAnswer($question, $validated['answer']),
                'points_earned' => 0, // Será calculado depois
            ]
        );
        
        // Calcular pontos se for questão objetiva
        if (in_array($question->type, ['multiple_choice', 'true_false'])) {
            $points = $answer->is_correct ? $question->points : 0;
            $answer->update(['points_earned' => $points]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Resposta salva com sucesso!'
        ]);
    }
    
    /**
     * Finalizar avaliação
     */
    public function submit(StudentAssessment $studentAssessment)
    {
        $user = Auth::user();
        
        if ($studentAssessment->student_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }
        
        if ($studentAssessment->status !== 'in_progress') {
            return redirect()->route('student.assessments.index')
                ->with('error', 'Avaliação já foi concluída.');
        }
        
        DB::transaction(function () use ($studentAssessment) {
            // Calcular pontuação total
            $totalScore = $studentAssessment->answers()->sum('points_earned');
            
            // Finalizar avaliação
            $studentAssessment->update([
                'status' => 'completed',
                'finished_at' => now(),
                'score' => $totalScore,
            ]);
            
            // Gerar analytics das questões
            $this->generateQuestionAnalytics($studentAssessment);
        });
        
        $settings = $studentAssessment->assessment->settings ?? [];
        
        // Sempre redirecionar para os resultados após finalizar
        return redirect()->route('student.assessments.result', $studentAssessment)
            ->with('success', 'Avaliação finalizada com sucesso!');
    }
    
    /**
     * Ver resultado da avaliação
     */
    public function result(StudentAssessment $studentAssessment)
    {
        $user = Auth::user();
        
        if ($studentAssessment->student_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }
        
        if ($studentAssessment->status !== 'completed') {
            return redirect()->route('student.assessments.index')
                ->with('error', 'Avaliação ainda não foi concluída.');
        }
        
        $assessment = $studentAssessment->assessment;
        $settings = $assessment->settings ?? [];
        
        // Verificar se pode ver resultados
        // Alunos podem sempre ver seus próprios resultados de avaliações concluídas
        // Professores e admins também podem ver
        if (!$user->isStudent() && !$user->isTeacher() && !$user->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
        
        // Carregar dados para o resultado
        $studentAssessment->load(['answers.question.options', 'assessment.subject']);
        
        // Calcular estatísticas
        $totalQuestions = $assessment->questions->count();
        $answeredQuestions = $studentAssessment->answers->count();
        $correctAnswers = $studentAssessment->answers->where('is_correct', true)->count();
        $percentage = ($studentAssessment->score / $assessment->max_score) * 100;
        
        // Verificar se passou
        $passingScore = $settings['passing_score'] ?? 60;
        $passed = $percentage >= $passingScore;
        
        return view('student.assessments.result', compact(
            'studentAssessment',
            'assessment',
            'totalQuestions',
            'answeredQuestions',
            'correctAnswers',
            'percentage',
            'passed',
            'settings'
        ));
    }
    
    /**
     * Verificar se o aluno pode fazer a avaliação
     */
    private function canTakeAssessment($user, $assessment)
    {
        // Verificar se está nas turmas
        $isEnrolled = $assessment->classes()
            ->whereHas('students', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->exists();
        
        if (!$isEnrolled) {
            return false;
        }
        
        // Verificar se está publicada
        if ($assessment->status !== 'published') {
            return false;
        }
        
        // Verificar datas
        if (now() < $assessment->opens_at || now() > $assessment->closes_at) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Verificar resposta
     */
    private function checkAnswer($question, $answer)
    {
        if ($question->type === 'essay') {
            return null; // Correção manual
        }
        
        if ($question->type === 'true_false') {
            $correctOption = $question->options->where('is_correct', true)->first();
            return $correctOption && $correctOption->content === $answer;
        }
        
        if ($question->type === 'multiple_choice') {
            $selectedOption = $question->options->where('content', $answer)->first();
            return $selectedOption && $selectedOption->is_correct;
        }
        
        return false;
    }
    
    /**
     * Gerar analytics das questões
     */
    private function generateQuestionAnalytics($studentAssessment)
    {
        foreach ($studentAssessment->answers as $answer) {
            // Buscar ou criar analytic para esta questão/avaliação
            $analytic = \App\Models\QuestionAnalytic::firstOrCreate(
                [
                    'question_id' => $answer->question_id,
                    'assessment_id' => $studentAssessment->assessment_id,
                ],
                [
                    'total_attempts' => 0,
                    'correct_attempts' => 0,
                    'average_time_seconds' => 0,
                ]
            );
            
            // Atualizar estatísticas
            $analytic->increment('total_attempts');
            if ($answer->is_correct) {
                $analytic->increment('correct_attempts');
            }
            
            // Atualizar timestamp manualmente
            $analytic->update(['updated_at' => now()]);
        }
    }
}