<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Resultado: {{ $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Resultado Geral -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 text-center">
                    <div class="text-6xl mb-4">
                        @if($studentAssessment->score >= 70) 🎉
                        @elseif($studentAssessment->score >= 50) 😊
                        @else 😔 @endif
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Avaliação Concluída!
                    </h1>
                    
                    <div class="text-6xl font-bold mb-4
                        @if($studentAssessment->score >= 70) text-green-600
                        @elseif($studentAssessment->score >= 50) text-yellow-600
                        @else text-red-600 @endif">
                        {{ number_format($studentAssessment->score, 1) }}%
                    </div>
                    
                    <p class="text-lg text-gray-600">
                        Você obteve {{ $studentAssessment->points_earned }} de {{ $assessment->max_score }} pontos
                    </p>
                </div>
            </div>

            <!-- Estatísticas Detalhadas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Detalhes da Tentativa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $studentAssessment->answers->count() }}
                            </div>
                            <div class="text-sm text-gray-600">Questões Respondidas</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $studentAssessment->answers->where('is_correct', true)->count() }}
                            </div>
                            <div class="text-sm text-gray-600">Respostas Corretas</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $studentAssessment->duration_minutes }}min
                            </div>
                            <div class="text-sm text-gray-600">Tempo Utilizado</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">📅 Iniciado em:</span>
                            {{ $studentAssessment->started_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">✅ Finalizado em:</span>
                            {{ $studentAssessment->finished_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revisão das Respostas -->
            @if($assessment->allow_review ?? true)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Revisão das Respostas</h3>
                        
                        <div class="space-y-6">
                            @foreach($studentAssessment->answers->sortBy('question.pivot.order') as $answer)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                                                Questão {{ $loop->iteration }}
                                            </span>
                                            <span class="text-xs font-medium px-2 py-1 rounded
                                                @if($answer->is_correct) bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @if($answer->is_correct) ✅ Correta @else ❌ Incorreta @endif
                                            </span>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                                {{ $answer->points_earned }}/{{ $answer->question->points }} pts
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $answer->question->title }}</h4>
                                    <p class="text-gray-700 mb-3">{{ $answer->question->content }}</p>
                                    
                                    @if($answer->question->type === 'essay')
                                        <div class="bg-gray-50 p-3 rounded mb-3">
                                            <span class="text-sm font-medium text-gray-700">Sua resposta:</span>
                                            <p class="mt-1 text-gray-900">{{ $answer->answer_text ?: 'Não respondida' }}</p>
                                        </div>
                                        
                                        @if($answer->feedback)
                                            <div class="bg-blue-50 p-3 rounded">
                                                <span class="text-sm font-medium text-blue-700">Feedback do professor:</span>
                                                <p class="mt-1 text-blue-900">{{ $answer->feedback }}</p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="space-y-2">
                                            @foreach($answer->question->options as $option)
                                                <div class="flex items-center p-2 rounded
                                                    @if($option->id === $answer->selected_option_id && $option->is_correct) bg-green-100 border border-green-300
                                                    @elseif($option->id === $answer->selected_option_id && !$option->is_correct) bg-red-100 border border-red-300
                                                    @elseif($option->is_correct) bg-green-50 border border-green-200
                                                    @else bg-gray-50 @endif">
                                                    
                                                    <span class="w-6 h-6 rounded-full border-2 mr-3 flex items-center justify-center
                                                        @if($option->id === $answer->selected_option_id && $option->is_correct) bg-green-500 border-green-500 text-white
                                                        @elseif($option->id === $answer->selected_option_id && !$option->is_correct) bg-red-500 border-red-500 text-white
                                                        @elseif($option->is_correct) bg-green-500 border-green-500 text-white
                                                        @else border-gray-300 @endif">
                                                        @if($option->id === $answer->selected_option_id) ● @endif
                                                        @if($option->is_correct && $option->id !== $answer->selected_option_id) ✓ @endif
                                                    </span>
                                                    
                                                    <span class="flex-1">{{ $option->content }}</span>
                                                    
                                                    @if($option->id === $answer->selected_option_id)
                                                        <span class="text-sm font-medium ml-2
                                                            @if($option->is_correct) text-green-700
                                                            @else text-red-700 @endif">
                                                            (Sua escolha)
                                                        </span>
                                                    @elseif($option->is_correct)
                                                        <span class="text-sm font-medium text-green-700 ml-2">
                                                            (Resposta correta)
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações -->
            <div class="flex justify-between items-center">
                <a href="{{ route('student.assessments.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar às Avaliações
                </a>
                
                <div class="space-x-2">
                    <button onclick="window.print()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        🖨️ Imprimir Resultado
                    </button>
                    
                    @if($assessment->allow_review ?? true)
                        <button onclick="downloadPDF()" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            📄 Baixar PDF
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            // Implementar download de PDF em versão futura
            alert('Funcionalidade de download PDF será implementada em breve.');
        }
    </script>
</x-app-layout>