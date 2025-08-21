<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Relatórios Gerais
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Estatísticas Gerais -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</div>
                        <div class="text-sm text-gray-600">Total de Usuários</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $stats['total_assessments'] }}</div>
                        <div class="text-sm text-gray-600">Total de Avaliações</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $stats['total_attempts'] }}</div>
                        <div class="text-sm text-gray-600">Tentativas Realizadas</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ $stats['total_questions'] }}</div>
                        <div class="text-sm text-gray-600">Total de Questões</div>
                    </div>
                </div>
            </div>

            <!-- Navegação de Relatórios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Relatórios Disponíveis</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.reports.users') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">👥</div>
                            <div class="font-medium text-blue-900">Relatório de Usuários</div>
                            <div class="text-sm text-blue-700">{{ $stats['total_users'] }} usuários cadastrados</div>
                        </a>
                        
                        <a href="{{ route('admin.reports.assessments') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📋</div>
                            <div class="font-medium text-green-900">Relatório de Avaliações</div>
                            <div class="text-sm text-green-700">{{ $stats['total_assessments'] }} avaliações criadas</div>
                        </a>
                        
                        <a href="{{ route('admin.reports.performance') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📈</div>
                            <div class="font-medium text-purple-900">Relatório de Desempenho</div>
                            <div class="text-sm text-purple-700">{{ $stats['completed_attempts'] }} tentativas concluídas</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Usuários por Mês -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📅 Novos Usuários (Últimos 6 Meses)</h3>
                    
                    <div class="space-y-2">
                        @foreach($usersByMonth as $month)
                            <div class="flex items-center">
                                <div class="w-20 text-sm text-gray-600">{{ \Carbon\Carbon::parse($month->month)->format('M/Y') }}</div>
                                <div class="flex-1 bg-gray-200 rounded-full h-4 ml-4">
                                    <div class="bg-blue-600 h-4 rounded-full" style="width: {{ ($month->count / $usersByMonth->max('count')) * 100 }}%"></div>
                                </div>
                                <div class="w-12 text-sm text-gray-600 text-right ml-2">{{ $month->count }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Avaliações -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🏆 Top 5 Avaliações Mais Realizadas</h3>
                        
                        <div class="space-y-3">
                            @foreach($topAssessments as $assessment)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-medium">{{ $assessment->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $assessment->student_assessments_count }} tentativas</div>
                                    </div>
                                    <div class="text-2xl">{{ $loop->iteration === 1 ? '🥇' : ($loop->iteration === 2 ? '🥈' : ($loop->iteration === 3 ? '🥉' : '🏅')) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Avaliações por Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Avaliações por Status</h3>
                        
                        <div class="space-y-3">
                            @foreach($assessmentsByStatus as $status)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="mr-2">
                                            @if($status->status === 'published') ✅
                                            @elseif($status->status === 'draft') 📝
                                            @elseif($status->status === 'closed') 🔒
                                            @else ❓ @endif
                                        </span>
                                        <span class="font-medium">
                                            @if($status->status === 'published') Publicadas
                                            @elseif($status->status === 'draft') Rascunhos
                                            @elseif($status->status === 'closed') Fechadas
                                            @else {{ ucfirst($status->status) }} @endif
                                        </span>
                                    </div>
                                    <span class="text-lg font-bold">{{ $status->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exportar Dados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">💾 Exportar Dados</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}" 
                           class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📄</div>
                            <div class="font-medium text-blue-900">Exportar Usuários</div>
                            <div class="text-sm text-blue-700">Formato CSV</div>
                        </a>
                        
                        <a href="{{ route('admin.reports.export', ['type' => 'assessments', 'format' => 'csv']) }}" 
                           class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📄</div>
                            <div class="font-medium text-green-900">Exportar Avaliações</div>
                            <div class="text-sm text-green-700">Formato CSV</div>
                        </a>
                        
                        <a href="{{ route('admin.reports.export', ['type' => 'performance', 'format' => 'csv']) }}" 
                           class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📄</div>
                            <div class="font-medium text-purple-900">Exportar Desempenho</div>
                            <div class="text-sm text-purple-700">Formato CSV</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>