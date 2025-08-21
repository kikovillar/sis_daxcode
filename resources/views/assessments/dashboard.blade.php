<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Dashboard de Avaliações
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Estatísticas Gerais -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📈 Visão Geral</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">📝</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-600">Total de Avaliações</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total_assessments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">✅</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-600">Publicadas</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $stats['published_assessments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">📝</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-yellow-600">Rascunhos</p>
                                    <p class="text-2xl font-bold text-yellow-900">{{ $stats['draft_assessments'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">👥</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-purple-600">Total de Tentativas</p>
                                    <p class="text-2xl font-bold text-purple-900">{{ $stats['total_attempts'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Atividade Mensal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📅 Atividade dos Últimos 6 Meses</h3>
                    
                    <div class="space-y-4">
                        @foreach($monthlyStats as $month)
                            <div class="flex items-center space-x-4">
                                <div class="w-16 text-sm font-medium text-gray-600">{{ $month['month'] }}</div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-4">
                                            <div class="bg-blue-500 h-4 rounded-full" 
                                                 style="width: {{ $month['assessments'] > 0 ? min(($month['assessments'] / max(array_column($monthlyStats, 'assessments'))) * 100, 100) : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $month['assessments'] }} avaliações</span>
                                    </div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" 
                                                 style="width: {{ $month['attempts'] > 0 ? min(($month['attempts'] / max(array_column($monthlyStats, 'attempts'))) * 100, 100) : 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $month['attempts'] }} tentativas</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Avaliações Recentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">🕒 Avaliações Recentes</h3>
                        
                        <div class="space-y-3">
                            @forelse($recentAssessments as $assessment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $assessment->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $assessment->subject->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $assessment->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($assessment->status === 'published') bg-green-100 text-green-800
                                            @elseif($assessment->status === 'draft') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $assessment->status }}
                                        </span>
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Nenhuma avaliação encontrada</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Avaliações -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">🏆 Avaliações Mais Populares</h3>
                        
                        <div class="space-y-3">
                            @forelse($topAssessments as $assessment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $assessment->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $assessment->subject->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-blue-600">{{ $assessment->student_assessments_count }}</p>
                                        <p class="text-xs text-gray-500">tentativas</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Nenhuma tentativa ainda</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas por Disciplina -->
            @if($subjectStats->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📚 Distribuição por Disciplina</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($subjectStats as $subject)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900">{{ $subject->name }}</h4>
                                    <p class="text-2xl font-bold text-blue-600">{{ $subject->assessments_count }}</p>
                                    <p class="text-sm text-gray-600">avaliações</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Questões Mais Utilizadas -->
            @if($topQuestions->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">❓ Questões Mais Utilizadas</h3>
                        
                        <div class="space-y-3">
                            @foreach($topQuestions as $question)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $question->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $question->subject->name }} • {{ $question->difficulty }}</p>
                                        <p class="text-xs text-gray-500">{{ Str::limit($question->content, 100) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($question->type === 'multiple_choice') bg-blue-100 text-blue-800
                                            @elseif($question->type === 'true_false') bg-green-100 text-green-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                            {{ $question->type }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">⚡ Ações Rápidas</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('assessments.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg text-center">
                            ➕ Nova Avaliação
                        </a>
                        
                        <a href="{{ route('questions.create') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-center">
                            ❓ Nova Questão
                        </a>
                        
                        <a href="{{ route('assessments.index') }}" 
                           class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg text-center">
                            📋 Ver Todas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>