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
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            📚
                        </span>
                        Disciplinas
                    </h2>
                    <p class="text-indigo-100 mt-1">Gerencie as disciplinas do sistema</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('subjects.create')); ?>" 
                       class="bg-emerald-500 bg-opacity-90 backdrop-blur-sm hover:bg-opacity-100 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>➕</span>
                        <span>Nova Disciplina</span>
                    </a>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Lista de Disciplinas -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <?php if(isset($subjects) && $subjects->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($subject->name); ?></h3>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        📚 Disciplina
                                    </span>
                                </div>
                                
                                <?php if($subject->description): ?>
                                    <p class="text-gray-600 mb-4"><?php echo e($subject->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                                    <div>
                                        <span class="font-medium">❓ Questões:</span><br>
                                        <?php echo e($subject->questions->count() ?? 0); ?>

                                    </div>
                                    <div>
                                        <span class="font-medium">📝 Avaliações:</span><br>
                                        <?php echo e($subject->assessments->count() ?? 0); ?>

                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('subjects.show', $subject)); ?>" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-center transition-colors">
                                        👁️ Ver
                                    </a>
                                    
                                    <a href="<?php echo e(route('subjects.edit', $subject)); ?>" 
                                       class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-lg text-center transition-colors">
                                        ✏️ Editar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📚</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma disciplina encontrada</h3>
                        <p class="text-gray-500 mb-6">Você ainda não criou nenhuma disciplina.</p>
                        <a href="<?php echo e(route('subjects.create')); ?>" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                            ➕ Criar Primeira Disciplina
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginação -->
            <?php if(isset($subjects)): ?>
                <?php if (isset($component)) { $__componentOriginal6b7f97cc5a6cdf26a8769abafc29181f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b7f97cc5a6cdf26a8769abafc29181f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pagination-wrapper','data' => ['paginator' => $subjects,'itemName' => 'disciplinas']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pagination-wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subjects),'item-name' => 'disciplinas']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6b7f97cc5a6cdf26a8769abafc29181f)): ?>
<?php $attributes = $__attributesOriginal6b7f97cc5a6cdf26a8769abafc29181f; ?>
<?php unset($__attributesOriginal6b7f97cc5a6cdf26a8769abafc29181f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6b7f97cc5a6cdf26a8769abafc29181f)): ?>
<?php $component = $__componentOriginal6b7f97cc5a6cdf26a8769abafc29181f; ?>
<?php unset($__componentOriginal6b7f97cc5a6cdf26a8769abafc29181f); ?>
<?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/subjects/index.blade.php ENDPATH**/ ?>