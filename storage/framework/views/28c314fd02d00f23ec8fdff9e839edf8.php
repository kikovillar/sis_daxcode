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
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ❓
                        </span>
                        Banco de Questões
                    </h2>
                    <p class="text-purple-100 mt-1">Gerencie suas questões e organize por disciplinas</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('questions.create')); ?>" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>➕</span>
                        <span>Nova Questão</span>
                    </a>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Lista de Questões -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <?php if(isset($questions) && $questions->count() > 0): ?>
                    <div class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900"><?php echo e($question->title); ?></h3>
                                            
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                <?php if($question->type === 'multiple_choice'): ?> bg-blue-100 text-blue-800
                                                <?php elseif($question->type === 'true_false'): ?> bg-green-100 text-green-800
                                                <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                                <?php if($question->type === 'multiple_choice'): ?> 🔘 Múltipla Escolha
                                                <?php elseif($question->type === 'true_false'): ?> ✅ Verdadeiro/Falso
                                                <?php else: ?> 📝 Dissertativa <?php endif; ?>
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-3"><?php echo e(Str::limit($question->content, 150)); ?></p>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">📚 Disciplina:</span><br>
                                                <?php echo e($question->subject->name); ?>

                                            </div>
                                            <div>
                                                <span class="font-medium">🏆 Pontos:</span><br>
                                                <?php echo e($question->points); ?>

                                            </div>
                                            <div>
                                                <span class="font-medium">📅 Criada:</span><br>
                                                <?php echo e($question->created_at->format('d/m/Y')); ?>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-6 flex flex-col space-y-2">
                                        <a href="<?php echo e(route('questions.show', $question)); ?>" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                                            👁️ Ver
                                        </a>
                                        
                                        <a href="<?php echo e(route('questions.edit', $question)); ?>" 
                                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                                            ✏️ Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">❓</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma questão encontrada</h3>
                        <p class="text-gray-500 mb-6">Você ainda não criou nenhuma questão.</p>
                        <a href="<?php echo e(route('questions.create')); ?>" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                            ➕ Criar Primeira Questão
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginação -->
            <?php if(isset($questions)): ?>
                <?php if (isset($component)) { $__componentOriginal6b7f97cc5a6cdf26a8769abafc29181f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b7f97cc5a6cdf26a8769abafc29181f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pagination-wrapper','data' => ['paginator' => $questions,'itemName' => 'questões']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pagination-wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($questions),'item-name' => 'questões']); ?>
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
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/questions/index.blade.php ENDPATH**/ ?>