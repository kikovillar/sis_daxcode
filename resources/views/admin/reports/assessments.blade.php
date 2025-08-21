<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            📝
                        </span>
                        Relatório de Avaliações
                    </h2>
                    <p class="text-blue-100 mt-1">Análise detalhada das avaliações do sistema</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('admin.reports.index') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Menu de Navegação dos Relatórios -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('admin.reports.index') }}" 
                           class="flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            <span class="mr-2">📊</span>
                            Dashboard Geral
                        </a>
                        <a href="{{ route('admin.reports.performance') }}" 
                           class="flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            <span class="mr-2">📈</span>
                            Performance
                        </a>
                        <a href="{{ route('admin.reports.assessments') }}" 
                           class="flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl shadow-lg">
                            <span class="mr-2">📝</span>
                            Avaliações
                        </a>
                        <a href="{{ route('admin.reports.students') }}" 
                           class="flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            <span class="mr-2">🎓</span>
                            Alunos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estatísticas de Avaliações -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['total_assessments'] }}</div>
                                <div class="text-blue-100 text-sm">Total de Avaliações</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">📝</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['published_assessments'] }}</div>
                                <div class="text-green-100 text-sm">Publicadas</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">✅</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['draft_assessments'] }}</div>
                                <div class="text-yellow-100 text-sm">Rascunhos</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">📝</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ number_format($stats['average_questions'], 1) }}</div>
                                <div class="text-purple-100 text-sm">Questões por Avaliação</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">❓</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Gráfico de Avaliações por Professor -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">👨‍🏫</span>
                            Avaliações por Professor
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="teachersChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Avaliações por Disciplina -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-green-100 text-green-600 rounded-lg p-2 mr-3">📚</span>
                            Avaliações por Disciplina
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="subjectsChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Criação Mensal -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden lg:col-span-2">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-purple-100 text-purple-600 rounded-lg p-2 mr-3">📅</span>
                            Criação de Avaliações ao Longo do Tempo
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="timelineChart" width="800" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabela Detalhada -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 rounded-lg p-2 mr-3">📋</span>
                        Detalhes das Avaliações
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avaliação</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disciplina</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questões</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tentativas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota Média</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($assessments as $assessment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $assessment->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($assessment->description, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $assessment->teacher->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $assessment->subject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($assessment->status === 'published') bg-green-100 text-green-800
                                                @elseif($assessment->status === 'draft') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @if($assessment->status === 'published') ✅ Publicada
                                                @elseif($assessment->status === 'draft') 📝 Rascunho
                                                @else 🔒 Fechada @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assessment->questions_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $assessment->attempts_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-lg font-bold text-blue-600">
                                                {{ $assessment->average_score ? number_format($assessment->average_score, 1) : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuração global dos gráficos
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = '#6B7280';

        // Gráfico de Avaliações por Professor
        const teachersCtx = document.getElementById('teachersChart').getContext('2d');
        new Chart(teachersCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($teacherData['labels']) !!},
                datasets: [{
                    label: 'Número de Avaliações',
                    data: {!! json_encode($teacherData['counts']) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Gráfico de Avaliações por Disciplina
        const subjectsCtx = document.getElementById('subjectsChart').getContext('2d');
        new Chart(subjectsCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($subjectData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($subjectData['counts']) !!},
                    backgroundColor: [
                        '#EF4444',
                        '#F59E0B',
                        '#10B981',
                        '#3B82F6',
                        '#8B5CF6',
                        '#EC4899',
                        '#06B6D4',
                        '#84CC16'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Gráfico de Timeline
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($timelineData['labels']) !!},
                datasets: [{
                    label: 'Avaliações Criadas',
                    data: {!! json_encode($timelineData['counts']) !!},
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>