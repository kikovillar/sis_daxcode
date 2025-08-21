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
                            🏫
                        </span>
                        Gerenciar Turmas
                    </h2>
                    <p class="text-blue-100 mt-1">Organize e gerencie suas turmas de forma eficiente</p>
                </div>
                
                <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('classes.create')); ?>" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span class="text-lg">➕</span>
                        <span>Nova Turma</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros Modernos -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">🔍</span>
                        Filtros de Busca
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('classes.index')); ?>" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                    <span class="mr-2">🔤</span>
                                    Buscar Turma
                                </label>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                       class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Digite o nome ou descrição da turma...">
                            </div>
                            
                            <?php if(auth()->user()->isAdmin()): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                        <span class="mr-2">👨‍🏫</span>
                                        Professor
                                    </label>
                                    <select name="teacher_id" 
                                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <option value="">Todos os professores</option>
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>" 
                                                    <?php echo e(request('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                                <?php echo e($teacher->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-end space-x-3">
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg">
                                    <span>🔍</span>
                                    <span>Filtrar</span>
                                </button>
                                <a href="<?php echo e(route('classes.index')); ?>" 
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2">
                                    <span>🔄</span>
                                    <span>Limpar</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Turmas Moderna -->
            <div class="space-y-6">
                <?php if($classes->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                <!-- Header do Card -->
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold mb-2 line-clamp-2"><?php echo e($class->name); ?></h3>
                                            <div class="flex items-center text-blue-100">
                                                <span class="mr-2">👨‍🏫</span>
                                                <span class="text-sm"><?php echo e($class->teacher->name); ?></span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <?php if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id): ?>
                                                <div class="relative group">
                                                    <button class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-2 transition-all duration-200">
                                                        ⚙️
                                                    </button>
                                                    <div class="absolute right-0 top-full mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2 min-w-[150px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                                        <a href="<?php echo e(route('classes.show', $class)); ?>" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                                            <span class="mr-2">👁️</span>
                                                            Ver detalhes
                                                        </a>
                                                        <a href="<?php echo e(route('classes.manage-students', $class)); ?>" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                                            <span class="mr-2">👥</span>
                                                            Gerenciar Alunos
                                                        </a>
                                                        <a href="<?php echo e(route('classes.edit', $class)); ?>" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 transition-colors">
                                                            <span class="mr-2">✏️</span>
                                                            Editar
                                                        </a>
                                                        <?php if(auth()->user()->isAdmin()): ?>
                                                            <form method="POST" action="<?php echo e(route('classes.destroy', $class)); ?>" 
                                                                  class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir esta turma?')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                                    <span class="mr-2">🗑️</span>
                                                                    Excluir
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Conteúdo do Card -->
                                <div class="p-6">
                                    <?php if($class->description): ?>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo e($class->description); ?></p>
                                    <?php endif; ?>
                                    
                                    <!-- Estatísticas -->
                                    <div class="grid grid-cols-3 gap-4 mb-6">
                                        <div class="text-center">
                                            <div class="bg-blue-100 rounded-xl p-3 mb-2">
                                                <span class="text-2xl">👥</span>
                                            </div>
                                            <div class="text-2xl font-bold text-blue-600"><?php echo e($class->students->count()); ?></div>
                                            <div class="text-xs text-gray-500">Alunos</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="bg-green-100 rounded-xl p-3 mb-2">
                                                <span class="text-2xl">📝</span>
                                            </div>
                                            <div class="text-2xl font-bold text-green-600"><?php echo e($class->assessments->count()); ?></div>
                                            <div class="text-xs text-gray-500">Avaliações</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="bg-purple-100 rounded-xl p-3 mb-2">
                                                <span class="text-2xl">📅</span>
                                            </div>
                                            <div class="text-sm font-bold text-purple-600"><?php echo e($class->created_at->format('d/m')); ?></div>
                                            <div class="text-xs text-gray-500">Criada</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Ações -->
                                    <div class="space-y-2">
                                        <a href="<?php echo e(route('classes.show', $class)); ?>" 
                                           class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg">
                                            <span>👁️</span>
                                            <span>Ver Detalhes</span>
                                        </a>
                                        <?php if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id): ?>
                                            <a href="<?php echo e(route('classes.manage-students', $class)); ?>" 
                                               class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                                                <span>👥</span>
                                                <span>Gerenciar Alunos</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                        
                    <!-- Paginação Moderna -->
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                            <?php if (isset($component)) { $__componentOriginal1c9f93a4a55ac41fe1d7ae66799ce105 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1c9f93a4a55ac41fe1d7ae66799ce105 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pagination-info','data' => ['paginator' => $classes,'itemName' => 'turmas']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pagination-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($classes),'item-name' => 'turmas']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1c9f93a4a55ac41fe1d7ae66799ce105)): ?>
<?php $attributes = $__attributesOriginal1c9f93a4a55ac41fe1d7ae66799ce105; ?>
<?php unset($__attributesOriginal1c9f93a4a55ac41fe1d7ae66799ce105); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1c9f93a4a55ac41fe1d7ae66799ce105)): ?>
<?php $component = $__componentOriginal1c9f93a4a55ac41fe1d7ae66799ce105; ?>
<?php unset($__componentOriginal1c9f93a4a55ac41fe1d7ae66799ce105); ?>
<?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Estado Vazio Moderno -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="text-center py-16 px-8">
                            <div class="mb-8">
                                <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                                    <span class="text-4xl">🏫</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                    <?php if(request()->hasAny(['search', 'teacher_id'])): ?>
                                        Nenhuma turma encontrada
                                    <?php else: ?>
                                        Suas turmas aparecerão aqui
                                    <?php endif; ?>
                                </h3>
                                <p class="text-gray-600 text-lg max-w-md mx-auto mb-8">
                                    <?php if(request()->hasAny(['search', 'teacher_id'])): ?>
                                        Não encontramos turmas com os filtros aplicados. Tente ajustar sua busca ou limpar os filtros.
                                    <?php else: ?>
                                        Organize seus alunos em turmas para facilitar o gerenciamento de avaliações e atividades.
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="space-y-4">
                                <?php if(request()->hasAny(['search', 'teacher_id'])): ?>
                                    <a href="<?php echo e(route('classes.index')); ?>" 
                                       class="inline-flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                                        <span>🔄</span>
                                        <span>Limpar Filtros</span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin()): ?>
                                    <div>
                                        <a href="<?php echo e(route('classes.create')); ?>" 
                                           class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-200 shadow-lg transform hover:scale-105">
                                            <span class="text-lg">➕</span>
                                            <span>Criar Primeira Turma</span>
                                        </a>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 max-w-4xl mx-auto">
                                        <div class="text-center p-6 bg-blue-50 rounded-xl">
                                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                                <span class="text-xl">👥</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Organize Alunos</h4>
                                            <p class="text-sm text-gray-600">Agrupe seus alunos em turmas para melhor organização</p>
                                        </div>
                                        <div class="text-center p-6 bg-green-50 rounded-xl">
                                            <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                                <span class="text-xl">📝</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Crie Avaliações</h4>
                                            <p class="text-sm text-gray-600">Aplique provas e exercícios específicos para cada turma</p>
                                        </div>
                                        <div class="text-center p-6 bg-purple-50 rounded-xl">
                                            <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                                <span class="text-xl">📊</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Acompanhe Progresso</h4>
                                            <p class="text-sm text-gray-600">Monitore o desempenho e evolução dos seus alunos</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
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
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/classes/index.blade.php ENDPATH**/ ?>