<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            🏠
                        </span>
                        Dashboard
                    </h2>
                    <p class="text-blue-100 mt-1">Bem-vindo ao Sistema de Avaliação Online</p>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">👋</span>
                        Olá, <?php echo e(Auth::user()->name); ?>!
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">
                        <?php if(Auth::user()->isAdmin()): ?>
                            Você está logado como <strong>Administrador</strong>
                        <?php elseif(Auth::user()->isTeacher()): ?>
                            Você está logado como <strong>Professor</strong>
                        <?php else: ?>
                            Você está logado como <strong>Aluno</strong>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="bg-blue-100 rounded-xl p-4 mb-3">
                                <span class="text-3xl">📊</span>
                            </div>
                            <h4 class="font-semibold text-gray-900">Sistema Ativo</h4>
                            <p class="text-sm text-gray-600">Plataforma funcionando perfeitamente</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-green-100 rounded-xl p-4 mb-3">
                                <span class="text-3xl">🚀</span>
                            </div>
                            <h4 class="font-semibold text-gray-900">Acesso Rápido</h4>
                            <p class="text-sm text-gray-600">Use o menu lateral para navegar</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-purple-100 rounded-xl p-4 mb-3">
                                <span class="text-3xl">💡</span>
                            </div>
                            <h4 class="font-semibold text-gray-900">Suporte</h4>
                            <p class="text-sm text-gray-600">Sistema moderno e intuitivo</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(Auth::user()->isAdmin()): ?>
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e($stats['total_users']); ?></div>
                                    <div class="text-blue-100 text-sm">Total de Usuários</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">👥</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Gerenciar Usuários →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e(\App\Models\ClassModel::count()); ?></div>
                                    <div class="text-green-100 text-sm">Total de Turmas</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">🏫</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('classes.index')); ?>" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Ver Turmas →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e($stats['total_assessments']); ?></div>
                                    <div class="text-purple-100 text-sm">Total de Avaliações</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">📝</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('assessments.index')); ?>" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Ver Avaliações →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e($stats['total_questions']); ?></div>
                                    <div class="text-yellow-100 text-sm">Total de Questões</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">❓</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('questions.index')); ?>" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                Banco de Questões →
                            </a>
                        </div>
                    </div>
                </div>

            <?php elseif(Auth::user()->isTeacher()): ?>
                <!-- Teacher Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e(Auth::user()->teachingClasses()->count()); ?></div>
                                    <div class="text-green-100 text-sm">Minhas Turmas</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">🏫</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('classes.index')); ?>" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Gerenciar Turmas →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e(Auth::user()->createdAssessments()->count()); ?></div>
                                    <div class="text-purple-100 text-sm">Minhas Avaliações</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">📝</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('assessments.index')); ?>" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Ver Avaliações →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e(Auth::user()->questions()->count()); ?></div>
                                    <div class="text-blue-100 text-sm">Minhas Questões</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">❓</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('questions.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Banco de Questões →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions for Teachers -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-emerald-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-emerald-100 text-emerald-600 rounded-lg p-2 mr-3">🚀</span>
                            Ações Rápidas
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="<?php echo e(route('assessments.create')); ?>" 
                               class="flex items-center p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200 border border-emerald-200">
                                <span class="text-2xl mr-4">➕</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Nova Avaliação</h4>
                                    <p class="text-sm text-gray-600">Criar uma nova prova ou exercício</p>
                                </div>
                            </a>
                            <a href="<?php echo e(route('classes.create')); ?>" 
                               class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 border border-blue-200">
                                <span class="text-2xl mr-4">🏗️</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Nova Turma</h4>
                                    <p class="text-sm text-gray-600">Criar e configurar uma nova turma</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Student Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e(Auth::user()->studentAssessments()->count()); ?></div>
                                    <div class="text-blue-100 text-sm">Avaliações Disponíveis</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">📝</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('student.assessments.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver Avaliações →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold"><?php echo e(Auth::user()->studentAssessments()->where('status', 'completed')->count()); ?></div>
                                    <div class="text-green-100 text-sm">Avaliações Concluídas</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">✅</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <a href="<?php echo e(route('student.assessments.index')); ?>" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Ver Resultados →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(Auth::user()->isAdmin()): ?>
                <!-- Gráficos de Relatórios (Apenas para Admin) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
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

                <!-- Gráfico de Atividade Mensal -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden lg:col-span-2">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-purple-100 text-purple-600 rounded-lg p-2 mr-3">📈</span>
                            Atividade dos Últimos 6 Meses
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="activityChart" width="800" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Atividade Recente -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 rounded-lg p-2 mr-3">🕒</span>
                        Atividade Recente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-r from-blue-400 to-purple-500 rounded-full w-10 h-10 flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-sm">📝</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900"><?php echo e($activity->title); ?></div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo e($activity->subject->name); ?> • <?php echo e($activity->teacher->name ?? 'Professor não definido'); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500"><?php echo e($activity->created_at->diffForHumans()); ?></div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        <?php if($activity->status === 'published'): ?> bg-green-100 text-green-800
                                        <?php elseif($activity->status === 'draft'): ?> bg-yellow-100 text-yellow-800
                                        <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                        <?php if($activity->status === 'published'): ?> ✅ Publicada
                                        <?php elseif($activity->status === 'draft'): ?> 📝 Rascunho
                                        <?php else: ?> 🔒 Fechada <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                </div>
            <?php endif; ?>

            <!-- System Info -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                <div class="text-center">
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Sistema de Avaliação Online</h4>
                    <p class="text-gray-600 text-sm">
                        Plataforma moderna para criação e aplicação de avaliações educacionais.
                        Use o menu lateral para navegar entre as funcionalidades.
                    </p>
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
                    data: [<?php echo e($stats['admins']); ?>, <?php echo e($stats['teachers']); ?>, <?php echo e($stats['students']); ?>],
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

        // Gráfico de Avaliações por Disciplina
        const subjectsCtx = document.getElementById('subjectsChart').getContext('2d');
        new Chart(subjectsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($assessmentsBySubject['labels']); ?>,
                datasets: [{
                    label: 'Número de Avaliações',
                    data: <?php echo json_encode($assessmentsBySubject['data']); ?>,
                    backgroundColor: [
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#EC4899',
                        '#06B6D4',
                        '#84CC16'
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
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($monthlyData['labels']); ?>,
                datasets: [{
                    label: 'Avaliações Criadas',
                    data: <?php echo json_encode($monthlyData['assessments']); ?>,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Tentativas de Alunos',
                    data: <?php echo json_encode($monthlyData['attempts']); ?>,
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
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/dashboard.blade.php ENDPATH**/ ?>