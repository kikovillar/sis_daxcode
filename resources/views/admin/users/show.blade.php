<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👤 Perfil de {{ $user->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Editar
                </a>
                <a href="{{ route('admin.users.export', $user) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    📊 Exportar Excel
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Dados Básicos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Dados Básicos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->name ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->email ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Usuário</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($user->role === 'admin') 👑 Administrador
                                @elseif($user->role === 'professor') 👨‍🏫 Professor
                                @else 👨‍🎓 Aluno @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->email_verified_at ? '✅ Ativo' : '❌ Inativo' }}
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
                            <p class="mt-1 text-sm text-gray-900">{{ $user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Idade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->idade ? $user->idade . ' anos' : 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sexo</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->sexo ? ucfirst($user->sexo) : 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">RG</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->rg ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CPF</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->cpf ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->telefone ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Benefício (BF)</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->bf ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado Civil</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->estado_civil ? ucfirst(str_replace('_', ' ', $user->estado_civil)) : 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tamanho da Camisa</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->tamanho_camisa ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tem Filhos</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($user->tem_filhos)
                                    <span class="text-green-600">✅ Sim</span>
                                    @if($user->quantidade_filhos)
                                        ({{ $user->quantidade_filhos }} {{ $user->quantidade_filhos == 1 ? 'filho' : 'filhos' }})
                                    @endif
                                @else
                                    <span class="text-gray-600">❌ Não</span>
                                @endif
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
                            <p class="mt-1 text-sm text-gray-900">{{ $user->endereco ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bairro</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->bairro ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cidade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->cidade ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->estado ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">CEP</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->cep ?: 'Não informado' }}</p>
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
                            <p class="mt-1 text-sm text-gray-900">{{ $user->urgencia_nome_contato ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone do Contato</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->urgencia_telefone_contato ?: 'Não informado' }}</p>
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
                            @if($user->documento_rg)
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="{{ Storage::url($user->documento_rg) }}" target="_blank" class="hover:underline">
                                        📄 Ver documento
                                    </a>
                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Documento CNH</label>
                            @if($user->documento_cnh)
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="{{ Storage::url($user->documento_cnh) }}" target="_blank" class="hover:underline">
                                        📄 Ver documento
                                    </a>
                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Documento CPF</label>
                            @if($user->documento_cpf)
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="{{ Storage::url($user->documento_cpf) }}" target="_blank" class="hover:underline">
                                        📄 Ver documento
                                    </a>
                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Foto 3x4</label>
                            @if($user->documento_foto_3x4)
                                <p class="mt-1 text-sm text-blue-600">
                                    <a href="{{ Storage::url($user->documento_foto_3x4) }}" target="_blank" class="hover:underline">
                                        🖼️ Ver foto
                                    </a>
                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-900">Não enviado</p>
                            @endif
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
                                @if($user->tem_deficiencia)
                                    <span class="text-orange-600">⚠️ Sim</span>
                                    @if($user->descricao_deficiencia)
                                        <br><span class="text-gray-600 text-xs">{{ $user->descricao_deficiencia }}</span>
                                    @endif
                                @else
                                    <span class="text-green-600">✅ Não</span>
                                @endif
                            </p>
                        </div>

                        <!-- Condição de Saúde -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Condição de Saúde Especial</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($user->tem_condicao_saude)
                                    <span class="text-orange-600">⚠️ Sim</span>
                                    @if($user->descricao_saude)
                                        <br><span class="text-gray-600 text-xs">{{ $user->descricao_saude }}</span>
                                    @endif
                                @else
                                    <span class="text-green-600">✅ Não</span>
                                @endif
                            </p>
                        </div>

                        <!-- Alergia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Possui Alergia</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($user->tem_alergia)
                                    <span class="text-red-600">🚨 Sim</span>
                                    @if($user->descricao_alergia)
                                        <br><span class="text-gray-600 text-xs">{{ $user->descricao_alergia }}</span>
                                    @endif
                                @else
                                    <span class="text-green-600">✅ Não</span>
                                @endif
                            </p>
                        </div>

                        <!-- Medicamento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Usa Medicamento</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($user->usa_medicamento)
                                    <span class="text-blue-600">💊 Sim</span>
                                    @if($user->qual_medicamento)
                                        <br><span class="text-gray-600 text-xs">{{ $user->qual_medicamento }}</span>
                                    @endif
                                @else
                                    <span class="text-green-600">✅ Não</span>
                                @endif
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
                            @if($user->ensino_pre_escolar)
                                <span class="text-green-600">✅</span>
                                <span class="ml-2 font-medium">Ensino Pré-escolar</span>
                            @else
                                <span class="text-gray-400">⭕</span>
                                <span class="ml-2 text-gray-500">Ensino Pré-escolar</span>
                            @endif
                        </div>

                        <!-- Ensino Fundamental -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Fundamental</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    @if($user->ensino_fundamental_concluido)
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($user->ensino_fundamental_cursando)
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    @endif
                                </div>
                                @if($user->ensino_fundamental_instituicao)
                                    <p class="text-sm text-gray-600">Instituição: {{ $user->ensino_fundamental_instituicao }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Ensino Médio -->
                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Médio</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    @if($user->ensino_medio_concluido)
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($user->ensino_medio_cursando)
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    @endif
                                </div>
                                @if($user->ensino_medio_instituicao)
                                    <p class="text-sm text-gray-600">Instituição: {{ $user->ensino_medio_instituicao }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Ensino Técnico -->
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Técnico</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    @if($user->ensino_tecnico_concluido)
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($user->ensino_tecnico_cursando)
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    @endif
                                </div>
                                @if($user->ensino_tecnico_instituicao)
                                    <p class="text-sm text-gray-600">Instituição: {{ $user->ensino_tecnico_instituicao }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Ensino Superior -->
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Ensino Superior</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    @if($user->ensino_superior_concluido)
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($user->superior_cursando)
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($user->superior_trancado)
                                        <span class="text-red-600">⏸️</span>
                                        <span class="ml-2">Trancado</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está trancado</span>
                                    @endif
                                </div>
                                @if($user->superior_instituicao)
                                    <p class="text-sm text-gray-600">Instituição: {{ $user->superior_instituicao }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Pós-graduação -->
                        <div class="border-l-4 border-red-500 pl-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Pós-graduação</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    @if($user->pos_graduacao_concluido)
                                        <span class="text-green-600">✅</span>
                                        <span class="ml-2">Concluído</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não concluído</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    @if($user->pos_graduacao_cursando)
                                        <span class="text-yellow-600">🔄</span>
                                        <span class="ml-2">Cursando</span>
                                    @else
                                        <span class="text-gray-400">⭕</span>
                                        <span class="ml-2 text-gray-500">Não está cursando</span>
                                    @endif
                                </div>
                                @if($user->pos_graduacao_instituicao)
                                    <p class="text-sm text-gray-600">Instituição: {{ $user->pos_graduacao_instituicao }}</p>
                                @endif
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
                        @if($user->curso_1 || $user->curso_2 || $user->curso_3)
                            @if($user->curso_1)
                                <div class="border-l-4 border-blue-400 pl-4">
                                    <h4 class="font-semibold text-gray-700">{{ $user->curso_1 }}</h4>
                                    @if($user->curso_1_instituicao)
                                        <p class="text-sm text-gray-600">{{ $user->curso_1_instituicao }}</p>
                                    @endif
                                </div>
                            @endif
                            @if($user->curso_2)
                                <div class="border-l-4 border-green-400 pl-4">
                                    <h4 class="font-semibold text-gray-700">{{ $user->curso_2 }}</h4>
                                    @if($user->curso_2_instituicao)
                                        <p class="text-sm text-gray-600">{{ $user->curso_2_instituicao }}</p>
                                    @endif
                                </div>
                            @endif
                            @if($user->curso_3)
                                <div class="border-l-4 border-purple-400 pl-4">
                                    <h4 class="font-semibold text-gray-700">{{ $user->curso_3 }}</h4>
                                    @if($user->curso_3_instituicao)
                                        <p class="text-sm text-gray-600">{{ $user->curso_3_instituicao }}</p>
                                    @endif
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 italic">Nenhum curso extra informado</p>
                        @endif
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
                                @if($user->situacao_profissional)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ ucfirst($user->situacao_profissional) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Não informado</span>
                                @endif
                            </p>
                        </div>

                        <!-- Experiência 1 -->
                        @if($user->experiencia_1_instituicao || $user->experiencia_1_funcao)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-gray-700 mb-3">Experiência 1</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Empresa/Instituição</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_1_instituicao ?: 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Período</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_1_ano ?: 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Função/Cargo</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_1_funcao ?: 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Principais Atividades</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_1_atividades ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Experiência 2 -->
                        @if($user->experiencia_2_instituicao || $user->experiencia_2_funcao)
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-700 mb-3">Experiência 2</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Empresa/Instituição</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_2_instituicao ?: 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Período</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_2_ano ?: 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Função/Cargo</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_2_funcao ?: 'Não informado' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Principais Atividades</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->experiencia_2_atividades ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!$user->experiencia_1_instituicao && !$user->experiencia_1_funcao && !$user->experiencia_2_instituicao && !$user->experiencia_2_funcao)
                            <p class="text-gray-500 italic">Nenhuma experiência profissional informada</p>
                        @endif
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
                                @if($user->nivel_ingles)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ ucfirst($user->nivel_ingles) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Não informado</span>
                                @endif
                            </p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                @if($user->desenvolveu_sistemas)
                                    <span class="text-green-600">✅</span>
                                    <span class="ml-2">Desenvolveu sistemas web/mobile</span>
                                @else
                                    <span class="text-gray-400">❌</span>
                                    <span class="ml-2 text-gray-500">Não desenvolveu sistemas</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                @if($user->ja_empreendeu)
                                    <span class="text-green-600">✅</span>
                                    <span class="ml-2">Já empreendeu</span>
                                @else
                                    <span class="text-gray-400">❌</span>
                                    <span class="ml-2 text-gray-500">Nunca empreendeu</span>
                                @endif
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
                                @php
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
                                @endphp
                                @if($dias && count($dias) > 0)
                                    @foreach($dias as $dia)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            {{ $diasLabels[$dia] ?? ucfirst($dia) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500 text-sm">Nenhum dia informado</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Horário de Disponibilidade</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->disponibilidade_horario ?: 'Não informado' }}</p>
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
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>