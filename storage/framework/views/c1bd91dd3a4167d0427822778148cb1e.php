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
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="bg-white bg-opacity-20 rounded-xl p-3 mr-4">
                            <span class="text-2xl">🏫</span>
                        </div>
                        <div>
                            <h2 class="font-bold text-2xl text-white leading-tight">
                                <?php echo e($class->name); ?>

                            </h2>
                            <p class="text-blue-100 flex items-center mt-1">
                                <span class="mr-2">👨‍🏫</span>
                                Professor: <?php echo e($class->teacher->name); ?>

                            </p>
                        </div>
                    </div>
                    <?php if($class->description): ?>
                        <p class="text-blue-100 text-sm mt-2 max-w-2xl"><?php echo e($class->description); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="flex space-x-3">
                    <?php if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id): ?>
                        <a href="<?php echo e(route('classes.manage-students', $class)); ?>" 
                           class="bg-green-500 bg-opacity-90 backdrop-blur-sm hover:bg-opacity-100 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                            <span>👥</span>
                            <span>Gerenciar Alunos</span>
                        </a>
                        <a href="<?php echo e(route('classes.edit', $class)); ?>" 
                           class="bg-yellow-500 bg-opacity-90 backdrop-blur-sm hover:bg-opacity-100 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                            <span>✏️</span>
                            <span>Editar</span>
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('classes.index')); ?>" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Informações da Turma -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Turma</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($class->name); ?></dd>
                                </div>
                                <?php if($class->description): ?>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                                        <dd class="text-sm text-gray-900"><?php echo e($class->description); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Professor Responsável</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($class->teacher->name); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Criada em</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($class->created_at->format('d/m/Y H:i')); ?></dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600"><?php echo e($stats['total_students']); ?></div>
                                    <div class="text-sm text-blue-800">Alunos</div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600"><?php echo e($stats['total_assessments']); ?></div>
                                    <div class="text-sm text-green-800">Avaliações</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gerenciar Alunos -->
            <?php if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Alunos Matriculados</h3>
                            <?php if($availableStudents->count() > 0): ?>
                                <button onclick="openAddStudentModal()" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Adicionar Aluno
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if($class->students->count() > 0): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nome
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Matriculado em
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $class->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo e($student->name); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo e($student->email); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo e($student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d/m/Y') : 'N/A'); ?>

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form method="POST" action="<?php echo e(route('classes.remove-student', [$class, $student])); ?>" 
                                                          class="inline" onsubmit="return confirm('Tem certeza que deseja remover este aluno da turma?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900">
                                                            🗑️ Remover
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="text-gray-500 text-lg mb-4">👥</div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhum aluno matriculado</h4>
                                <p class="text-gray-500 mb-4">Comece adicionando alunos à turma.</p>
                                <?php if($availableStudents->count() > 0): ?>
                                    <button onclick="openAddStudentModal()" 
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        ➕ Adicionar Primeiro Aluno
                                    </button>
                                <?php else: ?>
                                    <p class="text-sm text-gray-400">Não há alunos disponíveis para adicionar.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Avaliações da Turma -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Avaliações</h3>
                        <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('assessments.create')); ?>?class_id=<?php echo e($class->id); ?>" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Nova Avaliação
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if($class->assessments->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php $__currentLoopData = $class->assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-2"><?php echo e($assessment->title); ?></h4>
                                    <div class="text-sm text-gray-500 space-y-1">
                                        <div>Status: 
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                <?php echo e($assessment->status === 'published' ? 'bg-green-100 text-green-800' : 
                                                   ($assessment->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                                                <?php echo e(ucfirst($assessment->status)); ?>

                                            </span>
                                        </div>
                                        <div>Questões: <?php echo e($assessment->questions->count()); ?></div>
                                        <div>Duração: <?php echo e($assessment->duration_minutes); ?> min</div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="<?php echo e(route('assessments.show', $assessment)); ?>" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Ver detalhes →
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg mb-4">📝</div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhuma avaliação criada</h4>
                            <p class="text-gray-500 mb-4">Crie avaliações para esta turma.</p>
                            <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin()): ?>
                                <a href="<?php echo e(route('assessments.create')); ?>?class_id=<?php echo e($class->id); ?>" 
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Criar Primeira Avaliação
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Adicionar Aluno -->
    <?php if($availableStudents->count() > 0 && (auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id)): ?>
        <div id="addStudentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Adicionar Aluno</h3>
                        <button onclick="closeAddStudentModal()" class="text-gray-400 hover:text-gray-600">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form method="POST" action="<?php echo e(route('classes.add-student', $class)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Aluno</label>
                            <select name="student_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Escolha um aluno</option>
                                <?php $__currentLoopData = $availableStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->email); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAddStudentModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Adicionar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function openAddStudentModal() {
            document.getElementById('addStudentModal').classList.remove('hidden');
        }
        
        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
        }
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
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/classes/show.blade.php ENDPATH**/ ?>