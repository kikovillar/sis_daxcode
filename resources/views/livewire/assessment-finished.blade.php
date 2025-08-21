<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Ícone de Sucesso -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Título -->
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                @if($studentAssessment->status === 'completed')
                    Avaliação Finalizada!
                @else
                    Tempo Esgotado!
                @endif
            </h1>

            <p class="text-gray-600 mb-8">
                @if($studentAssessment->status === 'completed')
                    Sua avaliação foi enviada com sucesso.
                @else
                    Sua avaliação foi finalizada automaticamente devido ao tempo limite.
                @endif
            </p>

            <!-- Informações da Avaliação -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $assessment->title }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="text-left">
                        <span class="font-medium text-gray-700">Iniciada em:</span>
                        <div class="text-gray-600">
                            {{ $studentAssessment->started_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="text-left">
                        <span class="font-medium text-gray-700">Finalizada em:</span>
                        <div class="text-gray-600">
                            {{ $studentAssessment->finished_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="text-left">
                        <span class="font-medium text-gray-700">Tempo gasto:</span>
                        <div class="text-gray-600">
                            {{ gmdate('H:i:s', $studentAssessment->getElapsedTimeInSeconds()) }}
                        </div>
                    </div>
                    
                    <div class="text-left">
                        <span class="font-medium text-gray-700">Status:</span>
                        <div class="text-gray-600">
                            @if($studentAssessment->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Concluída
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Tempo Esgotado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            @php
                $stats = $studentAssessment->getStats();
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['answered_questions'] }}</div>
                    <div class="text-sm text-blue-800">de {{ $stats['total_questions'] }} questões</div>
                    <div class="text-xs text-blue-600 mt-1">respondidas</div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['correct_answers'] }}</div>
                    <div class="text-sm text-green-800">questões corretas</div>
                    <div class="text-xs text-green-600 mt-1">
                        {{ number_format($stats['accuracy_rate'], 1) }}% de acerto
                    </div>
                </div>
                
                @if($studentAssessment->score !== null)
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ number_format($studentAssessment->score, 1) }}
                        </div>
                        <div class="text-sm text-purple-800">
                            de {{ number_format($stats['max_score'], 1) }} pontos
                        </div>
                        <div class="text-xs text-purple-600 mt-1">
                            {{ number_format($stats['score_percentage'], 1) }}%
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-600">-</div>
                        <div class="text-sm text-gray-800">Aguardando</div>
                        <div class="text-xs text-gray-600 mt-1">correção</div>
                    </div>
                @endif
            </div>

            <!-- Informações Adicionais -->
            @if($studentAssessment->score === null)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <strong>Atenção:</strong> Sua avaliação contém questões dissertativas que precisam ser corrigidas pelo professor. 
                            A nota final será disponibilizada em breve.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('student.assessments.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Voltar às Avaliações
                </a>
                
                @if($studentAssessment->score !== null)
                    <a href="{{ route('student.assessment.result', $studentAssessment) }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Ver Resultado Detalhado
                    </a>
                @endif
            </div>

            <!-- Mensagem de Agradecimento -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Obrigado por participar da avaliação. Seus resultados foram registrados com segurança.
                </p>
            </div>
        </div>
    </div>
</div>