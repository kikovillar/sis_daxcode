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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👤 Perfil de <?php echo e($user->name); ?>

            </h2>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Editar
                </a>
                <a href="<?php echo e(route('admin.users.export', $user)); ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    📊 Exportar Excel
                </a>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Dados Básicos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Dados Básicos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->name ?? 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->email ?? 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Usuário</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->role === 'admin'): ?> 👑 Administrador
                                <?php elseif($user->role === 'professor'): ?> 👨‍🏫 Professor
                                <?php else: ?> 👨‍🎓 Aluno <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($user->email_verified_at ? '✅ Ativo' : '❌ Inativo'); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados Pessoais -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Dados Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Idade</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->idade ? $user->idade . ' anos' : 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sexo</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->sexo ? ucfirst($user->sexo) : 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">RG</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->rg ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CPF</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->cpf ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->telefone ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Benefício (BF)</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->bf ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado Civil</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->estado_civil ? ucfirst(str_replace('_', ' ', $user->estado_civil)) : 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tamanho da Camisa</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->tamanho_camisa ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tem Filhos</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->tem_filhos): ?>
                                    <span class="text-green-600">✅ Sim</span>
                                    <?php if($user->quantidade_filhos): ?>
                                        (<?php echo e($user->quantidade_filhos); ?> <?php echo e($user->quantidade_filhos == 1 ? 'filho' : 'filhos'); ?>)
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-600">❌ Não</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Endereço</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->endereco ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bairro</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->bairro ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cidade</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->cidade ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->estado ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CEP</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->cep ?: 'Não informado'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contato de Urgência -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Contato de Urgência</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome do Contato</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->urgencia_nome_contato ?: 'Não informado'); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone do Contato</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->urgencia_telefone_contato ?: 'Não informado'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Documentos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Documento RG</label>
                            <?php if($user->documento_rg): ?>
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="<?php echo e(Storage::url($user->documento_rg)); ?>" target="_blank" class="hover:underline">
                                        📄 Ver documento
                                    </a>
                                </p>
                            <?php else: ?>
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Documento CNH</label>
                            <?php if($user->documento_cnh): ?>
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="<?php echo e(Storage::url($user->documento_cnh)); ?>" target="_blank" class="hover:underline">
                                        📄 Ver documento
                                    </a>
                                </p>
                            <?php else: ?>
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Documento CPF</label>
                            <?php if($user->documento_cpf): ?>
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="<?php echo e(Storage::url($user->documento_cpf)); ?>" target="_blank" class="hover:underline">
                                        📄 Ver documento
                                    </a>
                                </p>
                            <?php else: ?>
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Foto 3x4</label>
                            <?php if($user->documento_foto_3x4): ?>
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="<?php echo e(Storage::url($user->documento_foto_3x4)); ?>" target="_blank" class="hover:underline">
                                        🖼️ Ver foto
                                    </a>
                                </p>
                            <?php else: ?>
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações de Saúde -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informações de Saúde</h3>
                    <div class="space-y-6">
                        <!-- Deficiência -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Possui Deficiência</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->tem_deficiencia): ?>
                                    <span class="text-orange-600">⚠️ Sim</span>
                                    <?php if($user->descricao_deficiencia): ?>
                                        <br><span class="text-gray-600 text-xs"><?php echo e($user->descricao_deficiencia); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-green-600">✅ Não</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Condição de Saúde -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Condição de Saúde Especial</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->tem_condicao_saude): ?>
                                    <span class="text-orange-600">⚠️ Sim</span>
                                    <?php if($user->descricao_saude): ?>
                                        <br><span class="text-gray-600 text-xs"><?php echo e($user->descricao_saude); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-green-600">✅ Não</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Alergia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Possui Alergia</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->tem_alergia): ?>
                                    <span class="text-red-600">🚨 Sim</span>
                                    <?php if($user->descricao_alergia): ?>
                                        <br><span class="text-gray-600 text-xs"><?php echo e($user->descricao_alergia); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-green-600">✅ Não</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Medicamento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Usa Medicamento</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->usa_medicamento): ?>
                                    <span class="text-blue-600">💊 Sim</span>
                                    <?php if($user->qual_medicamento): ?>
                                        <br><span class="text-gray-600 text-xs"><?php echo e($user->qual_medicamento); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-green-600">✅ Não</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Educação -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Educação</h3>
                    <div class="space-y-6">
                        <!-- Pré-escolar -->
                        <div class="flex items-center">
                            <?php if($user->ensino_pre_escolar): ?>
                                <span class="text-green-600">✅</span>
                                <span class="ml-2 font-medium">Ensino Pré-escolar</span>
                            <?php else: ?>
                                <span class="text-gray-400">⭕</span>
                                <span class="ml-2 text-gray-500">Ensino Pré-escolar</span>
                            <?php endif; ?>
                        </div>

                        <!-- Ensino Fundamental -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Fundamental</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <?php if($user->ensino_fundamental_concluido): ?>
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if($user->ensino_fundamental_cursando): ?>
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    <?php endif; ?>
                                </div>
                                <?php if($user->ensino_fundamental_instituicao): ?>
                                    <p class="text-sm text-gray-600">Instituição: <?php echo e($user->ensino_fundamental_instituicao); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ensino Médio -->
                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Médio</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <?php if($user->ensino_medio_concluido): ?>
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if($user->ensino_medio_cursando): ?>
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    <?php endif; ?>
                                </div>
                                <?php if($user->ensino_medio_instituicao): ?>
                                    <p class="text-sm text-gray-600">Instituição: <?php echo e($user->ensino_medio_instituicao); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ensino Técnico -->
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Técnico</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <?php if($user->ensino_tecnico_concluido): ?>
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if($user->ensino_tecnico_cursando): ?>
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    <?php endif; ?>
                                </div>
                                <?php if($user->ensino_tecnico_instituicao): ?>
                                    <p class="text-sm text-gray-600">Instituição: <?php echo e($user->ensino_tecnico_instituicao); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ensino Superior -->
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Superior</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <?php if($user->ensino_superior_concluido): ?>
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if($user->superior_cursando): ?>
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if($user->superior_trancado): ?>
                                        <span class="text-red-600">⏸️</span>
                                        <span class="ml-2">Trancado</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está trancado</span>
                                    <?php endif; ?>
                                </div>
                                <?php if($user->superior_instituicao): ?>
                                    <p class="text-sm text-gray-600">Instituição: <?php echo e($user->superior_instituicao); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Pós-graduação -->
                        <div class="border-l-4 border-red-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Pós-graduação</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <?php if($user->pos_graduacao_concluido): ?>
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if($user->pos_graduacao_cursando): ?>
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    <?php endif; ?>
                                </div>
                                <?php if($user->pos_graduacao_instituicao): ?>
                                    <p class="text-sm text-gray-600">Instituição: <?php echo e($user->pos_graduacao_instituicao); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cursos Extras -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Cursos Extras</h3>
                    <div class="space-y-4">
                        <?php if($user->curso_1 || $user->curso_2 || $user->curso_3): ?>
                            <?php if($user->curso_1): ?>
                                <div class="border-l-4 border-blue-400 pl-4">
                                    <h4 class="font-semibold text-gray-700"><?php echo e($user->curso_1); ?></h4>
                                    <?php if($user->curso_1_instituicao): ?>
                                        <p class="text-sm text-gray-600"><?php echo e($user->curso_1_instituicao); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($user->curso_2): ?>
                                <div class="border-l-4 border-green-400 pl-4">
                                    <h4 class="font-semibold text-gray-700"><?php echo e($user->curso_2); ?></h4>
                                    <?php if($user->curso_2_instituicao): ?>
                                        <p class="text-sm text-gray-600"><?php echo e($user->curso_2_instituicao); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($user->curso_3): ?>
                                <div class="border-l-4 border-purple-400 pl-4">
                                    <h4 class="font-semibold text-gray-700"><?php echo e($user->curso_3); ?></h4>
                                    <?php if($user->curso_3_instituicao): ?>
                                        <p class="text-sm text-gray-600"><?php echo e($user->curso_3_instituicao); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-gray-500 italic">Nenhum curso extra informado</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Experiência Profissional -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Experiência Profissional</h3>
                    <div class="space-y-6">
                        <!-- Situação Atual -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Situação Profissional Atual</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->situacao_profissional): ?>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        <?php echo e(ucfirst($user->situacao_profissional)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">Não informado</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Experiência 1 -->
                        <?php if($user->experiencia_1_instituicao || $user->experiencia_1_funcao): ?>
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-gray-700 mb-3">Experiência 1</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Empresa/Instituição</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_1_instituicao ?: 'Não informado'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Período</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_1_ano ?: 'Não informado'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Função/Cargo</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_1_funcao ?: 'Não informado'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Principais Atividades</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_1_atividades ?: 'Não informado'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Experiência 2 -->
                        <?php if($user->experiencia_2_instituicao || $user->experiencia_2_funcao): ?>
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-700 mb-3">Experiência 2</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Empresa/Instituição</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_2_instituicao ?: 'Não informado'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Período</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_2_ano ?: 'Não informado'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Função/Cargo</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_2_funcao ?: 'Não informado'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Principais Atividades</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($user->experiencia_2_atividades ?: 'Não informado'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(!$user->experiencia_1_instituicao && !$user->experiencia_1_funcao && !$user->experiencia_2_instituicao && !$user->experiencia_2_funcao): ?>
                            <p class="text-gray-500 italic">Nenhuma experiência profissional informada</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Habilidades -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Habilidades</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nível de Inglês</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <?php if($user->nivel_ingles): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        <?php echo e(ucfirst($user->nivel_ingles)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">Não informado</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <?php if($user->desenvolveu_sistemas): ?>
                                    <span class="text-green-600">✅</span>
                                    <span class="ml-2">Desenvolveu sistemas web/mobile</span>
                                <?php else: ?>
                                    <span class="text-gray-400">❌</span>
                                    <span class="ml-2 text-gray-500">Não desenvolveu sistemas</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center">
                                <?php if($user->ja_empreendeu): ?>
                                    <span class="text-green-600">✅</span>
                                    <span class="ml-2">Já empreendeu</span>
                                <?php else: ?>
                                    <span class="text-gray-400">❌</span>
                                    <span class="ml-2 text-gray-500">Nunca empreendeu</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disponibilidade -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Disponibilidade</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dias Disponíveis</label>
                            <div class="mt-1 flex flex-wrap gap-2">
                                <?php
                                    $dias = null;
                                    if ($user->disponibilidade_dias) {
                                        $dias = is_string($user->disponibilidade_dias) ? json_decode($user->disponibilidade_dias, true) : $user->disponibilidade_dias;
                                    }
                                    $diasLabels = [
                                        'segunda' => 'Segunda',
                                        'terca' => 'Terça', 
                                        'quarta' => 'Quarta',
                                        'quinta' => 'Quinta',
                                        'sexta' => 'Sexta',
                                        'sabado' => 'Sábado',
                                        'domingo' => 'Domingo'
                                    ];
                                ?>
                                <?php if($dias && count($dias) > 0): ?>
                                    <?php $__currentLoopData = $dias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            <?php echo e($diasLabels[$dia] ?? ucfirst($dia)); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-gray-500 text-sm">Nenhum dia informado</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Horário de Disponibilidade</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->disponibilidade_horario ?: 'Não informado'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Informações do Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cadastrado em</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($user->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
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
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/admin/users/show.blade.php ENDPATH**/ ?>