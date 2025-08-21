<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\StudentAssessment;
use App\Models\Question;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TakeAssessment extends Component
{
    public Assessment $assessment;
    public StudentAssessment $studentAssessment;
    public Question $currentQuestion;
    public $currentQuestionIndex = 0;
    public $questions = [];
    public $answers = [];
    public $timeRemaining = 0;
    public $isFinished = false;
    public $selectedAnswer = null;
    public $essayAnswer = '';
    public $showConfirmFinish = false;

    protected $listeners = [
        'timeExpired' => 'handleTimeExpired',
        'autoSave' => 'autoSaveAnswer'
    ];

    public function mount(Assessment $assessment)
    {
        $this->assessment = $assessment;

        // Verificar se o aluno pode fazer a avaliação
        if (!$this->assessment->canBeAttemptedBy(Auth::user())) {
            abort(403, 'Você não tem permissão para fazer esta avaliação.');
        }

        // Verificar se já existe uma tentativa ativa
        $this->studentAssessment = $this->assessment->getActiveAttemptFor(Auth::user());

        if (!$this->studentAssessment) {
            // Criar nova tentativa
            $this->studentAssessment = StudentAssessment::create([
                'assessment_id' => $this->assessment->id,
                'student_id' => Auth::id(),
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        // Verificar se o tempo não expirou
        if ($this->studentAssessment->hasTimeExpired()) {
            $this->handleTimeExpired();
            return;
        }

        $this->loadQuestions();
        $this->loadAnswers();
        $this->calculateTimeRemaining();
        $this->setCurrentQuestion();
    }

    public function loadQuestions()
    {
        $this->questions = $this->assessment->questions()
            ->orderBy('assessment_questions.order')
            ->get()
            ->toArray();
    }

    public function loadAnswers()
    {
        $existingAnswers = $this->studentAssessment->answers()
            ->get()
            ->keyBy('question_id');

        foreach ($this->questions as $question) {
            $answer = $existingAnswers->get($question['id']);
            
            if ($answer) {
                if ($question['type'] === 'essay') {
                    $this->answers[$question['id']] = $answer->answer_text;
                } else {
                    $this->answers[$question['id']] = $answer->selected_option_id;
                }
            }
        }
    }

    public function calculateTimeRemaining()
    {
        $this->timeRemaining = $this->studentAssessment->getRemainingTimeInSeconds();
    }

    public function setCurrentQuestion()
    {
        if (isset($this->questions[$this->currentQuestionIndex])) {
            $questionData = $this->questions[$this->currentQuestionIndex];
            $this->currentQuestion = Question::with('options')->find($questionData['id']);
            
            // Carregar resposta atual
            $questionId = $this->currentQuestion->id;
            if ($this->currentQuestion->type === 'essay') {
                $this->essayAnswer = $this->answers[$questionId] ?? '';
            } else {
                $this->selectedAnswer = $this->answers[$questionId] ?? null;
            }
        }
    }

    public function goToQuestion($index)
    {
        $this->saveCurrentAnswer();
        $this->currentQuestionIndex = $index;
        $this->setCurrentQuestion();
    }

    public function nextQuestion()
    {
        $this->saveCurrentAnswer();
        
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
            $this->setCurrentQuestion();
        }
    }

    public function previousQuestion()
    {
        $this->saveCurrentAnswer();
        
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->setCurrentQuestion();
        }
    }

    public function saveCurrentAnswer()
    {
        if (!$this->currentQuestion) {
            return;
        }

        $questionId = $this->currentQuestion->id;

        if ($this->currentQuestion->type === 'essay') {
            if (!empty($this->essayAnswer)) {
                $this->answers[$questionId] = $this->essayAnswer;
                $this->saveAnswer($questionId, ['text' => $this->essayAnswer]);
            }
        } else {
            if ($this->selectedAnswer) {
                $this->answers[$questionId] = $this->selectedAnswer;
                $this->saveAnswer($questionId, ['option_id' => $this->selectedAnswer]);
            }
        }
    }

    public function saveAnswer($questionId, $answerData)
    {
        try {
            DB::transaction(function () use ($questionId, $answerData) {
                $this->studentAssessment->saveAnswer($questionId, $answerData);
            });
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Erro ao salvar resposta. Tente novamente.'
            ]);
        }
    }

    public function autoSaveAnswer()
    {
        $this->saveCurrentAnswer();
        $this->dispatch('show-alert', [
            'type' => 'info',
            'message' => 'Resposta salva automaticamente.'
        ]);
    }

    public function confirmFinish()
    {
        $this->showConfirmFinish = true;
    }

    public function cancelFinish()
    {
        $this->showConfirmFinish = false;
    }

    public function finishAssessment()
    {
        $this->saveCurrentAnswer();
        
        try {
            DB::transaction(function () {
                $this->studentAssessment->finish();
            });
            
            $this->isFinished = true;
            $this->dispatch('assessment-finished');
            
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Erro ao finalizar avaliação. Tente novamente.'
            ]);
        }
    }

    public function handleTimeExpired()
    {
        $this->saveCurrentAnswer();
        
        try {
            DB::transaction(function () {
                $this->studentAssessment->expire();
            });
            
            $this->isFinished = true;
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'Tempo esgotado! Sua avaliação foi finalizada automaticamente.'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Erro ao processar expiração da avaliação.'
            ]);
        }
    }

    public function getProgress()
    {
        $totalQuestions = count($this->questions);
        $answeredQuestions = count(array_filter($this->answers));
        
        return $totalQuestions > 0 ? ($answeredQuestions / $totalQuestions) * 100 : 0;
    }

    public function isQuestionAnswered($questionIndex)
    {
        $questionId = $this->questions[$questionIndex]['id'] ?? null;
        return $questionId && isset($this->answers[$questionId]) && !empty($this->answers[$questionId]);
    }

    public function render()
    {
        if ($this->isFinished) {
            return view('livewire.assessment-finished', [
                'studentAssessment' => $this->studentAssessment,
                'assessment' => $this->assessment,
            ]);
        }

        return view('livewire.take-assessment', [
            'progress' => $this->getProgress(),
            'totalQuestions' => count($this->questions),
            'answeredQuestions' => count(array_filter($this->answers)),
        ]);
    }
}