<div class="min-h-screen bg-gray-50">
    <!-- Debug Info -->
    <div class="bg-yellow-100 p-4 mb-4 rounded">
        <h3 class="font-bold">Debug Info:</h3>
        <p>Assessment: {{ $assessment ? $assessment->title : 'NULL' }}</p>
        <p>Student Assessment: {{ $studentAssessment ? 'ID: ' . $studentAssessment->id : 'NULL' }}</p>
        <p>Time Remaining: {{ $timeRemaining ?? 'NULL' }}</p>
        <p>Current Question: {{ $currentQuestion ? 'ID: ' . $currentQuestion->id : 'NULL' }}</p>
        <p>Total Questions: {{ $totalQuestions ?? 'NULL' }}</p>
        <p>Current Question Index: {{ $currentQuestionIndex ?? 'NULL' }}</p>
    </div>

    <!-- Header com Timer e Progresso -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $assessment->title }}</h1>
                    <p class="text-sm text-gray-600">
                        Questão {{ $currentQuestionIndex + 1 }} de {{ $totalQuestions }}
                    </p>
                </div>
                
                <!-- Timer -->
                <div class="flex items-center space-x-4">
                    <div id="timer-container" class="bg-blue-100 text-blue-800 px-6 py-3 rounded-lg shadow-lg border-2 border-blue-200 transition-all duration-500">
                        <div class="text-center">
                            <div class="text-xs text-blue-600 mb-1">TEMPO RESTANTE</div>
                            <div class="flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/>
                                </svg>
                                <div id="timer-display" class="text-2xl font-mono font-bold">
                                    {{ sprintf('%02d:%02d:%02d', floor($timeRemaining / 3600), floor(($timeRemaining % 3600) / 60), $timeRemaining % 60) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Barra de Progresso -->
            <div class="pb-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-600 mt-1">
                    <span>{{ $answeredQuestions }} de {{ $totalQuestions }} respondidas</span>
                    <span>{{ number_format($progress, 1) }}% completo</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Navegação das Questões -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-4 sticky top-32">
                    <h3 class="font-medium text-gray-900 mb-3">Navegação</h3>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($questions as $index => $question)
                            <button wire:click="goToQuestion({{ $index }})"
                                    class="w-8 h-8 rounded text-xs font-medium transition-colors
                                           @if($index === $currentQuestionIndex)
                                               bg-blue-600 text-white
                                           @elseif($this->isQuestionAnswered($index))
                                               bg-green-100 text-green-800 hover:bg-green-200
                                           @else
                                               bg-gray-100 text-gray-600 hover:bg-gray-200
                                           @endif">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 space-y-2 text-xs">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-600 rounded mr-2"></div>
                            <span>Atual</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-100 border border-green-300 rounded mr-2"></div>
                            <span>Respondida</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-100 border border-gray-300 rounded mr-2"></div>
                            <span>Não respondida</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questão Atual -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow">
                    <!-- Cabeçalho da Questão -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ $currentQuestion->title }}
                                </h2>
                                <div class="flex items-center mt-1 space-x-4 text-sm text-gray-600">
                                    <span class="capitalize">{{ ucfirst($currentQuestion->difficulty) }}</span>
                                    <span>{{ $currentQuestion->points }} pontos</span>
                                    <span class="capitalize">
                                        @switch($currentQuestion->type)
                                            @case('multiple_choice')
                                                Múltipla Escolha
                                                @break
                                            @case('true_false')
                                                Verdadeiro/Falso
                                                @break
                                            @case('essay')
                                                Dissertativa
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo da Questão -->
                    <div class="px-6 py-6">
                        <div class="prose max-w-none mb-6">
                            {!! nl2br(e($currentQuestion->content)) !!}
                        </div>

                        <!-- Opções de Resposta -->
                        @if($currentQuestion->type === 'multiple_choice' || $currentQuestion->type === 'true_false')
                            <div class="space-y-3">
                                @foreach($currentQuestion->options as $option)
                                    <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors
                                                  {{ $selectedAnswer == $option->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <input type="radio" 
                                               wire:model.live="selectedAnswer" 
                                               value="{{ $option->id }}"
                                               class="mt-1 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-900">{{ $option->content }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($currentQuestion->type === 'essay')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sua resposta:
                                </label>
                                <textarea wire:model.live.debounce.500ms="essayAnswer"
                                          rows="8"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Digite sua resposta aqui..."></textarea>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ strlen($essayAnswer) }} caracteres
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Navegação -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <button wire:click="previousQuestion"
                                @if($currentQuestionIndex === 0) disabled @endif
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            ← Anterior
                        </button>

                        <div class="flex space-x-3">
                            <button wire:click="autoSaveAnswer"
                                    class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-300 rounded-md hover:bg-blue-200">
                                Salvar Resposta
                            </button>

                            @if($currentQuestionIndex === count($questions) - 1)
                                <button wire:click="confirmFinish"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">
                                    Finalizar Prova
                                </button>
                            @else
                                <button wire:click="nextQuestion"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                    Próxima →
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let timeRemaining = {{ $timeRemaining }};
                    const timerDisplay = document.getElementById('timer-display');
                    const timerContainer = document.getElementById('timer-container');
                    
                    function updateTimer() {
                        if (timeRemaining <= 0) {
                            timerDisplay.textContent = '00:00:00';
                            timerContainer.className = 'bg-red-100 text-red-800 px-6 py-3 rounded-lg shadow-lg border-2 border-red-200 transition-all duration-500';
                            
                            // Auto-submit quando tempo esgotar
                            setTimeout(() => {
                                @this.call('handleTimeExpired');
                            }, 1000);
                            return;
                        }
                        
                        const hours = Math.floor(timeRemaining / 3600);
                        const minutes = Math.floor((timeRemaining % 3600) / 60);
                        const seconds = timeRemaining % 60;
                        
                        const display = hours.toString().padStart(2, '0') + ':' + 
                                       minutes.toString().padStart(2, '0') + ':' + 
                                       seconds.toString().padStart(2, '0');
                        
                        timerDisplay.textContent = display;
                        
                        // Mudar cores baseado no tempo restante
                        if (timeRemaining <= 300) { // 5 minutos
                            timerContainer.className = 'bg-red-100 text-red-800 px-6 py-3 rounded-lg shadow-lg border-2 border-red-200 transition-all duration-500 timer-critical';
                        } else if (timeRemaining <= 600) { // 10 minutos
                            timerContainer.className = 'bg-yellow-100 text-yellow-800 px-6 py-3 rounded-lg shadow-lg border-2 border-yellow-200 transition-all duration-500 timer-warning';
                        } else {
                            timerContainer.className = 'bg-blue-100 text-blue-800 px-6 py-3 rounded-lg shadow-lg border-2 border-blue-200 transition-all duration-500';
                        }
                        
                        timeRemaining--;
                    }
                    
                    // Atualizar a cada segundo
                    setInterval(updateTimer, 1000);
                });
                </script>

                <style>
                    /* Timer animations */
                    .timer-warning {
                        animation: pulse 2s infinite;
                    }
                    
                    .timer-critical {
                        animation: blink 1s infinite;
                    }
                    
                    @keyframes pulse {
                        0%, 100% { opacity: 1; }
                        50% { opacity: 0.7; }
                    }
                    
                    @keyframes blink {
                        0%, 50% { opacity: 1; }
                        51%, 100% { opacity: 0.5; }
                    }
                </style>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    @if($showConfirmFinish)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900">Finalizar Avaliação</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Tem certeza que deseja finalizar a avaliação? 
                            Você respondeu {{ $answeredQuestions }} de {{ $totalQuestions }} questões.
                        </p>
                        <p class="text-sm text-red-600 mt-2">
                            Esta ação não pode ser desfeita.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button wire:click="cancelFinish"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button wire:click="finishAssessment"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                            Finalizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Auto-save a cada 30 segundos -->
    <script>
        setInterval(() => {
            @this.call('autoSaveAnswer');
        }, 30000);
    </script>
</div>