<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Resultado: {{ $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Resultado Geral -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 {{ $passed ? 'bg-green-100' : 'bg-red-100' }}">
                            <span class="text-3xl">{{ $passed ? '🎉' : '😔' }}</span>
                        </div>
                        
                        <h3 class="text-2xl font-bold {{ $passed ? 'text-green-600' : 'text-red-600' }} mb-2">
                            {{ $passed ? 'Parabéns! Você foi aprovado!' : 'Não foi desta vez...' }}
                        </h3>
                        
                        <p class="text-gray-600">
                            {{ $passed ? 'Você atingiu a nota mínima necessária.' : 'Continue estudando e tente novamente.' }}
                        </p>
                    </div>
                    
                    <!-- Estatísticas principais -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($studentAssessment->score, 1) }}</div>
                            <div class="text-sm text-gray-600">Sua Nota</div>
                            <div class="text-xs text-gray-500">de {{ $assessment->max_score }}</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($percentage, 1) }}%</div>
                            <div class="text-sm text-gray-600">Percentual</div>
                            <div class="text-xs text-gray-500">{{ $settings['passing_score'] ?? 60 }}% para aprovar</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $correctAnswers }}</div>
                            <div class="text-sm text-gray-600">Acertos</div>
                            <div class="text-xs text-gray-500">de {{ $totalQuestions }} questões</div>
                        </div>
                        
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">
                                @if($studentAssessment->started_at && $studentAssessment->finished_at)
                                    @php
                                        $totalSeconds = $studentAssessment->started_at->diffInSeconds($studentAssessment->finished_at);
                                        $hours = floor($totalSeconds / 3600);
                                        $minutes = floor(($totalSeconds % 3600) / 60);
                                        $seconds = $totalSeconds % 60;
                                        
                                        if ($hours > 0) {
                                            $timeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                        } else {
                                            $timeFormatted = sprintf('%02d:%02d', $minutes, $seconds);
                                        }
                                    @endphp
                                    {{ $timeFormatted }}
                                @else
                                    --:--
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">Tempo Utilizado</div>
                            <div class="text-xs text-gray-500">de {{ $assessment->duration_minutes }} min disponíveis</div>
                        </div>
                    </div>
                    
                    <!-- Informações da avaliação -->
                    <div class="border-t pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Informações da Avaliação</h4>
                                <div class="space-y-1 text-gray-600">
                                    <p><strong>Disciplina:</strong> {{ $assessment->subject->name }}</p>
                                    <p><strong>Concluída em:</strong> {{ $studentAssessment->finished_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Duração:</strong> {{ $assessment->duration_minutes }} minutos</p>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Seu Desempenho</h4>
                                <div class="space-y-1 text-gray-600">
                                    <p><strong>Questões respondidas:</strong> {{ $answeredQuestions }}/{{ $totalQuestions }}</p>
                                    <p><strong>Taxa de acerto:</strong> {{ $totalQuestions > 0 ? number_format(($correctAnswers / $totalQuestions) * 100, 1) : 0 }}%</p>
                                    <p><strong>Status:</strong> 
                                        <span class="{{ $passed ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $passed ? 'Aprovado' : 'Reprovado' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revisão das Questões -->
            @if($settings['allow_review'] ?? false)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📝 Revisão das Questões</h3>
                        
                        <div class="space-y-6">
                            @foreach($studentAssessment->answers as $answer)
                                @php
                                    $question = $answer->question;
                                    $isCorrect = $answer->is_correct;
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-medium text-gray-900">{{ $question->title }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $isCorrect ? 'bg-green-100 text-green-800' : ($isCorrect === false ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                @if($isCorrect === true)
                                                    ✅ Correto
                                                @elseif($isCorrect === false)
                                                    ❌ Incorreto
                                                @else
                                                    ⏳ Aguardando correção
                                                @endif
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                {{ number_format($answer->points_earned, 1) }}/{{ $question->pivot->points_override ?? $question->points }} pts
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 mb-3">{{ $question->content }}</p>
                                    
                                    @if($question->image_path)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $question->image_path) }}" 
                                                 alt="{{ $question->image_description ?? 'Imagem da questão' }}"
                                                 class="max-w-sm h-auto rounded-lg">
                                        </div>
                                    @endif
                                    
                                    <!-- Sua resposta -->
                                    <div class="bg-gray-50 p-3 rounded-lg mb-3">
                                        <h5 class="font-medium text-gray-900 mb-2">Sua resposta:</h5>
                                        <p class="text-gray-700">{{ $answer->answer_text }}</p>
                                    </div>
                                    
                                    <!-- Resposta correta (para questões objetivas) -->
                                    @if($question->isObjective() && !$isCorrect)
                                        @php
                                            $correctOption = $question->options->where('is_correct', true)->first();
                                        @endphp
                                        @if($correctOption)
                                            <div class="bg-green-50 p-3 rounded-lg mb-3">
                                                <h5 class="font-medium text-green-900 mb-2">Resposta correta:</h5>
                                                <p class="text-green-700">{{ $correctOption->content }}</p>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <!-- Explicação -->
                                    @if($settings['question_feedback'] ?? false && $question->explanation)
                                        <div class="bg-blue-50 p-3 rounded-lg">
                                            <h5 class="font-medium text-blue-900 mb-2">💡 Explicação:</h5>
                                            <p class="text-blue-700">{{ $question->explanation }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex space-x-4">
                            <a href="{{ route('student.assessments.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← Voltar às Avaliações
                            </a>
                            
                            @if($settings['feedback_enabled'] ?? false)
                                <button onclick="openFeedbackModal()" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    💬 Dar Feedback
                                </button>
                            @endif
                        </div>
                        
                        <div class="flex space-x-4">
                            @if($passed && ($settings['certificate_template'] ?? false))
                                <a href="{{ route('student.certificate', $studentAssessment) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    🏆 Baixar Certificado
                                </a>
                            @endif
                            
                            <button onclick="window.print()" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                🖨️ Imprimir Resultado
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Feedback -->
    @if($settings['feedback_enabled'] ?? false)
        <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">💬 Feedback sobre a Avaliação</h3>
                    
                    <form method="POST" action="{{ route('student.feedback', $studentAssessment) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Como você avalia a dificuldade da avaliação?
                                </label>
                                <select name="difficulty_rating" class="block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1">1 - Muito fácil</option>
                                    <option value="2">2 - Fácil</option>
                                    <option value="3" selected>3 - Adequada</option>
                                    <option value="4">4 - Difícil</option>
                                    <option value="5">5 - Muito difícil</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    O tempo foi suficiente?
                                </label>
                                <select name="time_rating" class="block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1">1 - Muito pouco tempo</option>
                                    <option value="2">2 - Pouco tempo</option>
                                    <option value="3" selected>3 - Tempo adequado</option>
                                    <option value="4">4 - Tempo sobrou</option>
                                    <option value="5">5 - Muito tempo</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Comentários (opcional)
                                </label>
                                <textarea name="comments" rows="3" 
                                          class="block w-full border-gray-300 rounded-md shadow-sm"
                                          placeholder="Deixe seus comentários sobre a avaliação..."></textarea>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2 mt-6">
                            <button type="button" onclick="closeFeedbackModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enviar Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openFeedbackModal() {
            document.getElementById('feedbackModal').classList.remove('hidden');
        }
        
        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }
        
        // Estilo de impressão
        window.addEventListener('beforeprint', function() {
            document.body.classList.add('print-mode');
        });
        
        window.addEventListener('afterprint', function() {
            document.body.classList.remove('print-mode');
        });
    </script>

    <style>
        @media print {
            .print-mode {
                -webkit-print-color-adjust: exact;
            }
            
            /* Esconder elementos desnecessários na impressão */
            nav, .no-print, button {
                display: none !important;
            }
            
            /* Ajustar layout para impressão */
            .container {
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</x-app-layout>