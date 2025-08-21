<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $assessment->title }} - Sistema de Avaliação</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <div x-data="assessmentApp()" x-init="init()" class="min-h-screen">
        <!-- Header com Timer e Progresso -->
        <div class="bg-white shadow-sm border-b sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $assessment->title }}</h1>
                        <p class="text-sm text-gray-600">
                            Questão <span x-text="currentQuestionIndex + 1"></span> de {{ $questions->count() }}
                        </p>
                    </div>
                    
                    <!-- Timer -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 text-blue-800 px-6 py-3 rounded-lg shadow-lg border-2 border-blue-200"
                             :class="timeRemaining < 300 ? 'bg-red-100 text-red-800 border-red-200 animate-pulse' : ''">
                            <div class="text-center">
                                <div class="text-2xl font-bold" x-text="formatTime(timeRemaining)"></div>
                                <div class="text-xs">Tempo restante</div>
                            </div>
                        </div>
                        
                        <button @click="showConfirmFinish = true" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Finalizar Prova
                        </button>
                    </div>
                </div>
                
                <!-- Barra de Progresso -->
                <div class="pb-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                             :style="`width: ${((currentQuestionIndex + 1) / {{ $questions->count() }}) * 100}%`"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação entre questões -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($questions as $index => $question)
                        <button @click="goToQuestion({{ $index }})"
                                class="w-10 h-10 rounded-lg border-2 text-sm font-medium transition-all"
                                :class="getQuestionButtonClass({{ $index }})">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Conteúdo da Questão -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-lg p-8">
                @foreach($questions as $index => $question)
                    <div x-show="currentQuestionIndex === {{ $index }}" class="space-y-6">
                        <!-- Cabeçalho da Questão -->
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start mb-2">
                                <h2 class="text-lg font-semibold text-gray-900">{{ $question->title }}</h2>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $question->points }} pontos
                                </span>
                            </div>
                            <div class="text-gray-700 whitespace-pre-wrap">{!! nl2br(e($question->content)) !!}</div>
                        </div>

                        <!-- Opções de Resposta -->
                        <div class="space-y-4">
                            @if($question->type === 'multiple_choice')
                                <!-- Múltipla Escolha -->
                                <div class="space-y-3">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                               :class="answers[{{ $question->id }}] === '{{ $option->content }}' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                            <input type="radio" 
                                                   name="question_{{ $question->id }}"
                                                   value="{{ $option->content }}"
                                                   x-model="answers[{{ $question->id }}]"
                                                   @change="saveAnswer({{ $question->id }}, '{{ $option->content }}')"
                                                   class="mr-3 text-blue-600">
                                            <span class="flex-1">{{ $option->content }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($question->type === 'true_false')
                                <!-- Verdadeiro ou Falso -->
                                <div class="space-y-3">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                               :class="answers[{{ $question->id }}] === '{{ $option->content }}' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                                            <input type="radio" 
                                                   name="question_{{ $question->id }}"
                                                   value="{{ $option->content }}"
                                                   x-model="answers[{{ $question->id }}]"
                                                   @change="saveAnswer({{ $question->id }}, '{{ $option->content }}')"
                                                   class="mr-3 text-blue-600">
                                            <span class="flex-1 font-medium">{{ $option->content }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($question->type === 'essay')
                                <!-- Dissertativa -->
                                <div>
                                    <textarea x-model="answers[{{ $question->id }}]"
                                              @input="debounceAutoSave({{ $question->id }}, $event.target.value)"
                                              placeholder="Digite sua resposta aqui..."
                                              rows="8"
                                              class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ $existingAnswers[$question->id] ?? '' }}</textarea>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <span x-text="(answers[{{ $question->id }}] || '').length"></span> caracteres
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Navegação -->
                        <div class="flex justify-between pt-6 border-t">
                            <button @click="previousQuestion()" 
                                    x-show="currentQuestionIndex > 0"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                                ← Anterior
                            </button>
                            
                            <div class="flex space-x-3">
                                <button @click="saveCurrentAnswer()" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                    Salvar Resposta
                                </button>
                                
                                <button @click="nextQuestion()" 
                                        x-show="currentQuestionIndex < {{ $questions->count() - 1 }}"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                                    Próxima →
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modal de Confirmação para Finalizar -->
        <div x-show="showConfirmFinish" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md mx-4">
                <h3 class="text-lg font-semibold mb-4">Finalizar Avaliação</h3>
                <p class="text-gray-600 mb-6">
                    Tem certeza que deseja finalizar a avaliação? Esta ação não pode ser desfeita.
                </p>
                <div class="flex space-x-3">
                    <button @click="showConfirmFinish = false" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button @click="submitAssessment()" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-colors">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div x-show="loading" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2 text-center">Salvando...</p>
            </div>
        </div>
    </div>

    <script>
        function assessmentApp() {
            return {
                answers: @json($existingAnswers),
                currentQuestionIndex: 0,
                timeRemaining: {{ $timeRemaining * 60 }}, // Convert to seconds
                showConfirmFinish: false,
                loading: false,
                autoSaveTimeout: null,

                init() {
                    // Start timer
                    this.startTimer();
                    
                    // Auto-save every 30 seconds
                    setInterval(() => {
                        this.autoSaveAll();
                    }, 30000);

                    // Warn before leaving page
                    window.addEventListener('beforeunload', (e) => {
                        e.preventDefault();
                        e.returnValue = '';
                    });
                },

                startTimer() {
                    const timer = setInterval(() => {
                        this.timeRemaining--;
                        
                        if (this.timeRemaining <= 0) {
                            clearInterval(timer);
                            this.submitAssessment();
                        }
                    }, 1000);
                },

                formatTime(seconds) {
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const secs = Math.floor(seconds % 60);
                    
                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },

                goToQuestion(index) {
                    this.currentQuestionIndex = index;
                },

                nextQuestion() {
                    if (this.currentQuestionIndex < {{ $questions->count() - 1 }}) {
                        this.currentQuestionIndex++;
                    }
                },

                previousQuestion() {
                    if (this.currentQuestionIndex > 0) {
                        this.currentQuestionIndex--;
                    }
                },

                getQuestionButtonClass(index) {
                    const questionId = {{ json_encode($questions->pluck('id')->toArray()) }}[index];
                    const hasAnswer = this.answers[questionId];
                    const isCurrent = this.currentQuestionIndex === index;
                    
                    if (isCurrent) {
                        return 'border-blue-500 bg-blue-500 text-white';
                    } else if (hasAnswer) {
                        return 'border-green-500 bg-green-100 text-green-800';
                    } else {
                        return 'border-gray-300 bg-white text-gray-600 hover:bg-gray-50';
                    }
                },

                saveAnswer(questionId, answer) {
                    this.answers[questionId] = answer;
                    this.sendAnswerToServer(questionId, answer);
                },

                saveCurrentAnswer() {
                    const questionId = {{ json_encode($questions->pluck('id')->toArray()) }}[this.currentQuestionIndex];
                    const answer = this.answers[questionId];
                    
                    if (answer) {
                        this.sendAnswerToServer(questionId, answer);
                    }
                },

                debounceAutoSave(questionId, value) {
                    this.answers[questionId] = value;
                    
                    clearTimeout(this.autoSaveTimeout);
                    this.autoSaveTimeout = setTimeout(() => {
                        this.sendAnswerToServer(questionId, value);
                    }, 2000);
                },

                sendAnswerToServer(questionId, answer) {
                    if (!answer) return;
                    
                    this.loading = true;
                    
                    fetch('{{ url('/student/assessments/' . $studentAssessment->id . '/save-answer') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            answer: answer
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Resposta salva:', data.message);
                        } else {
                            console.error('Erro ao salvar:', data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                },

                autoSaveAll() {
                    Object.keys(this.answers).forEach(questionId => {
                        const answer = this.answers[questionId];
                        if (answer) {
                            this.sendAnswerToServer(parseInt(questionId), answer);
                        }
                    });
                },

                submitAssessment() {
                    this.loading = true;
                    
                    // Save all answers first
                    this.autoSaveAll();
                    
                    // Submit assessment
                    setTimeout(() => {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("student.assessments.submit", $studentAssessment) }}';
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        form.appendChild(csrfToken);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }, 1000);
                }
            }
        }
    </script>
</body>
</html>