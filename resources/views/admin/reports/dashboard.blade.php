<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            📊
                        </span>
                        Dashboard de Relatórios
                    </h2>
                    <p class="text-purple-100 mt-1">Análises e estatísticas do sistema</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Estatísticas Gerais -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['total_users'] }}</div>
                                <div class="text-blue-100 text-sm">Total de Usuários</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">👥</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['total_assessments'] }}</div>
                                <div class="text-green-100 text-sm">Avaliações</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">📝</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['total_questions'] }}</div>
                                <div class="text-purple-100 text-sm">Questões</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">❓</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $stats['total_attempts'] }}</div>
                                <div class="text-orange-100 text-sm">Tentativas</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">🎯</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Gráfico de Usuários por Tipo -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">👥</span>
                            Distribuição de Usuários
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="usersChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Avaliações por Status -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-green-100 text-green-600 rounded-lg p-2 mr-3">📝</span>
                            Status das Avaliações
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="assessmentsChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Desempenho Mensal -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-purple-100 text-purple-600 rounded-lg p-2 mr-3">📈</span>
                            Atividade Mensal
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="monthlyChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Notas -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-yellow-100 text-yellow-600 rounded-lg p-2 mr-3">🏆</span>
                            Distribuição de Notas
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="gradesChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabelas de Dados -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Top Avaliações -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-indigo-100 text-indigo-600 rounded-lg p-2 mr-3">🏅</span>
                            Avaliações Mais Realizadas
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($topAssessments as $assessment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $assessment->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $assessment->subject->name }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-indigo-600">{{ $assessment->attempts_count }}</div>
                                        <div class="text-xs text-gray-500">tentativas</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Professores -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-green-100 text-green-600 rounded-lg p-2 mr-3">👨‍🏫</span>
                            Professores Mais Ativos
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($topTeachers as $teacher)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-green-400 to-teal-500 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">
                                                {{ substr($teacher->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $teacher->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $teacher->email }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-600">{{ $teacher->assessments_count }}</div>
                                        <div class="text-xs text-gray-500">avaliações</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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

        // Gráfico de Usuários por Tipo
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Administradores', 'Professores', 'Alunos'],
                datasets: [{
                    data: [{{ $stats['admins'] }}, {{ $stats['teachers'] }}, {{ $stats['students'] }}],
                    backgroundColor: [
                        '#EF4444',
                        '#3B82F6',
                        '#10B981'
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

        // Gráfico de Avaliações por Status
        const assessmentsCtx = document.getElementById('assessmentsChart').getContext('2d');
        new Chart(assessmentsCtx, {
            type: 'bar',
            data: {
                labels: ['Rascunho', 'Publicadas', 'Fechadas'],
                datasets: [{
                    data: [{{ $stats['draft_assessments'] }}, {{ $stats['published_assessments'] }}, {{ $stats['closed_assessments'] }}],
                    backgroundColor: [
                        '#F59E0B',
                        '#10B981',
                        '#EF4444'
                    ],
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

        // Gráfico de Atividade Mensal
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyData['labels']) !!},
                datasets: [{
                    label: 'Avaliações Criadas',
                    data: {!! json_encode($monthlyData['assessments']) !!},
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Tentativas de Alunos',
                    data: {!! json_encode($monthlyData['attempts']) !!},
                    borderColor: '#06B6D4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
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

        // Gráfico de Distribuição de Notas
        const gradesCtx = document.getElementById('gradesChart').getContext('2d');
        new Chart(gradesCtx, {
            type: 'bar',
            data: {
                labels: ['0-2', '2-4', '4-6', '6-8', '8-10'],
                datasets: [{
                    label: 'Número de Alunos',
                    data: {!! json_encode($gradesDistribution) !!},
                    backgroundColor: [
                        '#EF4444',
                        '#F59E0B',
                        '#F59E0B',
                        '#10B981',
                        '#059669'
                    ],
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
    </script>
</x-app-layout>