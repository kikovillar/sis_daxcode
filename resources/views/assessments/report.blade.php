<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Relatório: {{ $assessment->title }}
            </h2>
            
            <div class="flex space-x-2">
                <a href="{{ route('assessments.show', $assessment) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Informações da Avaliação -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">📋 Informações Gerais</h3>
                            <div class="space-y-2 text-sm">
                                <p><strong>Disciplina:</strong> {{ $assessment->subject->name }}</p>
                                <p><strong>Duração:</strong> {{ $assessment->duration_minutes }} minutos</p>
                                <p><strong>Pontuação Máxima:</strong> {{ $assessment->max_score }} pontos</p>
                                <p><strong>Status:</strong> 
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($assessment->status === 'published') bg-green-100 text-green-800
                                        @elseif($assessment->status === 'draft') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $assessment->status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">📅 Período</h3>
                            <div class="space-y-2 text-sm">
                                <p><strong>Abertura:</strong> {{ $assessment->opens_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Fechamento:</strong> {{ $assessment->closes_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Questões:</strong> {{ $assessment->questions->count() }}</p>
                                @if($avgDuration)
                                    <p><strong>Tempo Médio:</strong> {{ round($avgDuration) }} minutos</p>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">👥 Turmas</h3>
                            <div class="space-y-1 text-sm">
                                @foreach($assessment->classes as $class)
                                    <p>• {{ $class->name }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Principais -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📈 Estatísticas Principais</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_attempts'] }}</div>
                            <div class="text-sm text-gray-600">Total de Tentativas</div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['completed_attempts'] }}</div>
                            <div class="text-sm text-gray-600">Concluídas</div>
                        </div>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['average_score'], 1) }}</div>
                            <div class="text-sm text-gray-600">Nota Média</div>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['pass_rate'], 1) }}%</div>
                            <div class="text-sm text-gray-600">Taxa de Aprovação</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-gray-600">{{ number_format($stats['highest_score'], 1) }}</div>
                            <div class="text-sm text-gray-600">Maior Nota</div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-gray-600">{{ number_format($stats['lowest_score'], 1) }}</div>
                            <div class="text-sm text-gray-600">Menor Nota</div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-xl font-bold text-gray-600">{{ number_format($stats['median_score'], 1) }}</div>
                            <div class="text-sm text-gray-600">Mediana</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribuição de Notas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Distribuição de Notas</h3>
                    
                    <div class="space-y-3">
                        @foreach($scoreDistribution as $range => $count)
                            <div class="flex items-center space-x-4">
                                <div class="w-20 text-sm font-medium text-gray-600">{{ $range }}</div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-6">
                                            <div class="bg-blue-500 h-6 rounded-full flex items-center justify-center text-white text-xs font-medium" 
                                                 style="width: {{ $stats['completed_attempts'] > 0 ? ($count / $stats['completed_attempts']) * 100 : 0 }}%">
                                                @if($count > 0) {{ $count }} @endif
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-16">{{ $count }} alunos</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Análise por Questão -->
            @if(count($questionAnalysis) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">❓ Análise por Questão</h3>
                        
                        <div class="space-y-4">
                            @foreach($questionAnalysis as $analysis)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $analysis['question']->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($analysis['question']->content, 100) }}</p>
                                        </div>
                                        <div class="ml-4 text-right">
                                            <div class="text-lg font-bold 
                                                @if($analysis['accuracy_rate'] >= 80) text-green-600
                                                @elseif($analysis['accuracy_rate'] >= 60) text-yellow-600
                                                @else text-red-600 @endif">
                                                {{ number_format($analysis['accuracy_rate'], 1) }}%
                                            </div>
                                            <div class="text-xs text-gray-500">acertos</div>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium">Respostas:</span> {{ $analysis['total_answers'] }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Acertos:</span> {{ $analysis['correct_answers'] }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Pontos Médios:</span> {{ number_format($analysis['avg_points'], 1) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Barra de progresso -->
                                    <div class="mt-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full 
                                                @if($analysis['accuracy_rate'] >= 80) bg-green-500
                                                @elseif($analysis['accuracy_rate'] >= 60) bg-yellow-500
                                                @else bg-red-500 @endif" 
                                                 style="width: {{ $analysis['accuracy_rate'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>