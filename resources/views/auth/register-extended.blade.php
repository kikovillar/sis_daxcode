@php
    $isEdit = isset($user) && $user->exists;
    $formAction = $isEdit ? route('admin.users.update', $user) : route('admin.users.store');
    $formMethod = $isEdit ? 'PATCH' : 'POST';
@endphp

<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-red-500">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Há alguns erros com sua submissão:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-green-500">✅</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @if($isEdit)
                    @method('PATCH')
                @endif

                <!-- Dados Básicos -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-full p-2 mr-3">👤</span>
                        Dados Básicos
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Senha {{ $isEdit ? '(deixe em branco para manter a atual)' : '*' }}
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                   {{ !$isEdit ? 'required' : '' }}>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Senha {{ $isEdit ? '(se alterando)' : '*' }}
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Usuário *</label>
                            <select name="role" id="role" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                    required>
                                <option value="">Selecione...</option>
                                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="professor" {{ old('role', $user->role ?? '') == 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="aluno" {{ old('role', $user->role ?? '') == 'aluno' ? 'selected' : '' }}>Aluno</option>
                            </select>
                        </div>

                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
                            <input type="file" name="profile_photo" id="profile_photo" 
                                   accept="image/jpeg,image/jpg,image/png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">JPG, JPEG ou PNG. Máximo 2MB.</p>
                            @if($isEdit && $user->profile_photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto atual" class="w-16 h-16 rounded-full object-cover">
                                    <p class="text-xs text-gray-500">Foto atual</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dados Pessoais -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-green-100 text-green-600 rounded-full p-2 mr-3">📋</span>
                        Dados Pessoais
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" id="data_nascimento" 
                                   value="{{ old('data_nascimento', $user->data_nascimento ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="idade" class="block text-sm font-medium text-gray-700 mb-2">Idade</label>
                            <input type="number" name="idade" id="idade" min="1" max="120"
                                   value="{{ old('idade', $user->idade ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                            <select name="sexo" id="sexo" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione...</option>
                                <option value="masculino" {{ old('sexo', $user->sexo ?? '') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="feminino" {{ old('sexo', $user->sexo ?? '') == 'feminino' ? 'selected' : '' }}>Feminino</option>
                                <option value="outro" {{ old('sexo', $user->sexo ?? '') == 'outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                        </div>

                        <div>
                            <label for="rg" class="block text-sm font-medium text-gray-700 mb-2">RG</label>
                            <input type="text" name="rg" id="rg" 
                                   value="{{ old('rg', $user->rg ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">CPF</label>
                            <input type="text" name="cpf" id="cpf" 
                                   value="{{ old('cpf', $user->cpf ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                            <input type="text" name="telefone" id="telefone" 
                                   value="{{ old('telefone', $user->telefone ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="bf" class="block text-sm font-medium text-gray-700 mb-2">Benefício (BF)</label>
                            <input type="text" name="bf" id="bf" 
                                   value="{{ old('bf', $user->bf ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Ex: Bolsa Família, Auxílio Brasil">
                        </div>

                        <div>
                            <label for="bf" class="block text-sm font-medium text-gray-700 mb-2">Benefício (BF)</label>
                            <input type="text" name="bf" id="bf" 
                                   value="{{ old('bf', $user->bf ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Ex: Bolsa Família">
                        </div>

                        <div>
                            <label for="bf" class="block text-sm font-medium text-gray-700 mb-2">Benefício (BF)</label>
                            <input type="text" name="bf" id="bf" 
                                   value="{{ old('bf', $user->bf ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Ex: Bolsa Família">
                        </div>

                        <div>
                            <label for="estado_civil" class="block text-sm font-medium text-gray-700 mb-2">Estado Civil</label>
                            <select name="estado_civil" id="estado_civil" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione...</option>
                                <option value="solteiro" {{ old('estado_civil', $user->estado_civil ?? '') == 'solteiro' ? 'selected' : '' }}>Solteiro</option>
                                <option value="casado" {{ old('estado_civil', $user->estado_civil ?? '') == 'casado' ? 'selected' : '' }}>Casado</option>
                                <option value="divorciado" {{ old('estado_civil', $user->estado_civil ?? '') == 'divorciado' ? 'selected' : '' }}>Divorciado</option>
                                <option value="viuvo" {{ old('estado_civil', $user->estado_civil ?? '') == 'viuvo' ? 'selected' : '' }}>Viúvo</option>
                                <option value="uniao_estavel" {{ old('estado_civil', $user->estado_civil ?? '') == 'uniao_estavel' ? 'selected' : '' }}>União Estável</option>
                            </select>
                        </div>

                        <div>
                            <label for="tamanho_camisa" class="block text-sm font-medium text-gray-700 mb-2">Tamanho da Camisa</label>
                            <select name="tamanho_camisa" id="tamanho_camisa" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione...</option>
                                <option value="PP" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'PP' ? 'selected' : '' }}>PP</option>
                                <option value="P" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'P' ? 'selected' : '' }}>P</option>
                                <option value="M" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="G" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'G' ? 'selected' : '' }}>G</option>
                                <option value="GG" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'GG' ? 'selected' : '' }}>GG</option>
                                <option value="XG" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'XG' ? 'selected' : '' }}>XG</option>
                                <option value="XXG" {{ old('tamanho_camisa', $user->tamanho_camisa ?? '') == 'XXG' ? 'selected' : '' }}>XXG</option>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="hidden" name="tem_filhos" value="0">
                                    <input type="checkbox" name="tem_filhos" id="tem_filhos" value="1" 
                                           {{ old('tem_filhos', $user->tem_filhos ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="tem_filhos" class="ml-2 block text-sm text-gray-900">Tem filhos</label>
                                </div>
                                
                                <div id="quantidade_filhos_container" style="display: {{ old('tem_filhos', $user->tem_filhos ?? false) ? 'block' : 'none' }};">
                                    <label for="quantidade_filhos" class="block text-sm font-medium text-gray-700 mb-1">Quantos?</label>
                                    <input type="number" name="quantidade_filhos" id="quantidade_filhos" min="0" max="20"
                                           value="{{ old('quantidade_filhos', $user->quantidade_filhos ?? '') }}" 
                                           class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-purple-100 text-purple-600 rounded-full p-2 mr-3">🏠</span>
                        Endereço
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                            <input type="text" name="endereco" id="endereco" 
                                   value="{{ old('endereco', $user->endereco ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                            <input type="text" name="bairro" id="bairro" 
                                   value="{{ old('bairro', $user->bairro ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                            <input type="text" name="cidade" id="cidade" 
                                   value="{{ old('cidade', $user->cidade ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <input type="text" name="estado" id="estado" maxlength="2"
                                   value="{{ old('estado', $user->estado ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Ex: SP">
                        </div>

                        <div>
                            <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                            <input type="text" name="cep" id="cep" 
                                   value="{{ old('cep', $user->cep ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="00000-000">
                        </div>
                    </div>
                </div>

                <!-- Contato de Urgência -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-red-100 text-red-600 rounded-full p-2 mr-3">🚨</span>
                        Contato de Urgência
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="urgencia_nome_contato" class="block text-sm font-medium text-gray-700 mb-2">Nome do Contato</label>
                            <input type="text" name="urgencia_nome_contato" id="urgencia_nome_contato" 
                                   value="{{ old('urgencia_nome_contato', $user->urgencia_nome_contato ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="urgencia_telefone_contato" class="block text-sm font-medium text-gray-700 mb-2">Telefone do Contato</label>
                            <input type="text" name="urgencia_telefone_contato" id="urgencia_telefone_contato" 
                                   value="{{ old('urgencia_telefone_contato', $user->urgencia_telefone_contato ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-yellow-100 text-yellow-600 rounded-full p-2 mr-3">📄</span>
                        Documentos
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="documento_rg" class="block text-sm font-medium text-gray-700 mb-2">Documento RG</label>
                            <input type="file" name="documento_rg" id="documento_rg" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, JPEG ou PNG. Máximo 2MB.</p>
                            @if($isEdit && $user->documento_rg)
                                <p class="text-xs text-green-600 mt-1">✅ Documento enviado</p>
                            @endif
                        </div>

                        <div>
                            <label for="documento_cnh" class="block text-sm font-medium text-gray-700 mb-2">Documento CNH</label>
                            <input type="file" name="documento_cnh" id="documento_cnh" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, JPEG ou PNG. Máximo 2MB.</p>
                            @if($isEdit && $user->documento_cnh)
                                <p class="text-xs text-green-600 mt-1">✅ Documento enviado</p>
                            @endif
                        </div>

                        <div>
                            <label for="documento_cpf" class="block text-sm font-medium text-gray-700 mb-2">Documento CPF</label>
                            <input type="file" name="documento_cpf" id="documento_cpf" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, JPEG ou PNG. Máximo 2MB.</p>
                            @if($isEdit && $user->documento_cpf)
                                <p class="text-xs text-green-600 mt-1">✅ Documento enviado</p>
                            @endif
                        </div>

                        <div>
                            <label for="documento_foto_3x4" class="block text-sm font-medium text-gray-700 mb-2">Foto 3x4</label>
                            <input type="file" name="documento_foto_3x4" id="documento_foto_3x4" 
                                   accept=".jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">JPG, JPEG ou PNG. Máximo 2MB.</p>
                            @if($isEdit && $user->documento_foto_3x4)
                                <p class="text-xs text-green-600 mt-1">✅ Documento enviado</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informações de Saúde -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-pink-100 text-pink-600 rounded-full p-2 mr-3">🏥</span>
                        Informações de Saúde
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Deficiência -->
                        <div>
                            <div class="flex items-center mb-3">
                                <input type="hidden" name="tem_deficiencia" value="0">
                                <input type="checkbox" name="tem_deficiencia" id="tem_deficiencia" value="1" 
                                       {{ old('tem_deficiencia', $user->tem_deficiencia ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="tem_deficiencia" class="ml-2 block text-sm font-medium text-gray-900">Possui alguma deficiência</label>
                            </div>
                            <div id="descricao_deficiencia_container" style="display: {{ old('tem_deficiencia', $user->tem_deficiencia ?? false) ? 'block' : 'none' }};">
                                <label for="descricao_deficiencia" class="block text-sm font-medium text-gray-700 mb-2">Descrição da deficiência</label>
                                <textarea name="descricao_deficiencia" id="descricao_deficiencia" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('descricao_deficiencia', $user->descricao_deficiencia ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Condição de Saúde -->
                        <div>
                            <div class="flex items-center mb-3">
                                <input type="hidden" name="tem_condicao_saude" value="0">
                                <input type="checkbox" name="tem_condicao_saude" id="tem_condicao_saude" value="1" 
                                       {{ old('tem_condicao_saude', $user->tem_condicao_saude ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="tem_condicao_saude" class="ml-2 block text-sm font-medium text-gray-900">Possui condição de saúde especial</label>
                            </div>
                            <div id="descricao_saude_container" style="display: {{ old('tem_condicao_saude', $user->tem_condicao_saude ?? false) ? 'block' : 'none' }};">
                                <label for="descricao_saude" class="block text-sm font-medium text-gray-700 mb-2">Descrição da condição</label>
                                <textarea name="descricao_saude" id="descricao_saude" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('descricao_saude', $user->descricao_saude ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Alergia -->
                        <div>
                            <div class="flex items-center mb-3">
                                <input type="hidden" name="tem_alergia" value="0">
                                <input type="checkbox" name="tem_alergia" id="tem_alergia" value="1" 
                                       {{ old('tem_alergia', $user->tem_alergia ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="tem_alergia" class="ml-2 block text-sm font-medium text-gray-900">Possui alguma alergia</label>
                            </div>
                            <div id="descricao_alergia_container" style="display: {{ old('tem_alergia', $user->tem_alergia ?? false) ? 'block' : 'none' }};">
                                <label for="descricao_alergia" class="block text-sm font-medium text-gray-700 mb-2">Descrição da alergia</label>
                                <textarea name="descricao_alergia" id="descricao_alergia" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('descricao_alergia', $user->descricao_alergia ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Medicamento -->
                        <div>
                            <div class="flex items-center mb-3">
                                <input type="hidden" name="usa_medicamento" value="0">
                                <input type="checkbox" name="usa_medicamento" id="usa_medicamento" value="1" 
                                       {{ old('usa_medicamento', $user->usa_medicamento ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="usa_medicamento" class="ml-2 block text-sm font-medium text-gray-900">Usa medicamento contínuo</label>
                            </div>
                            <div id="qual_medicamento_container" style="display: {{ old('usa_medicamento', $user->usa_medicamento ?? false) ? 'block' : 'none' }};">
                                <label for="qual_medicamento" class="block text-sm font-medium text-gray-700 mb-2">Qual medicamento</label>
                                <textarea name="qual_medicamento" id="qual_medicamento" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('qual_medicamento', $user->qual_medicamento ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Educação -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-full p-2 mr-3">🎓</span>
                        Educação
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Pré-escolar -->
                        <div class="flex items-center">
                            <input type="hidden" name="ensino_pre_escolar" value="0">
                            <input type="checkbox" name="ensino_pre_escolar" id="ensino_pre_escolar" value="1" 
                                   {{ old('ensino_pre_escolar', $user->ensino_pre_escolar ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="ensino_pre_escolar" class="ml-2 block text-sm text-gray-900">Ensino Pré-escolar</label>
                        </div>

                        <!-- Ensino Fundamental -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Ensino Fundamental</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_fundamental_status" id="ensino_fundamental_nao" value="nao" 
                                           {{ (!old('ensino_fundamental_concluido', $user->ensino_fundamental_concluido ?? false) && !old('ensino_fundamental_cursando', $user->ensino_fundamental_cursando ?? false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_fundamental_nao" class="ml-2 block text-sm text-gray-900">Não cursou</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_fundamental_status" id="ensino_fundamental_cursando" value="cursando" 
                                           {{ old('ensino_fundamental_cursando', $user->ensino_fundamental_cursando ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_fundamental_cursando" class="ml-2 block text-sm text-gray-900">Cursando</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_fundamental_status" id="ensino_fundamental_concluido" value="concluido" 
                                           {{ old('ensino_fundamental_concluido', $user->ensino_fundamental_concluido ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_fundamental_concluido" class="ml-2 block text-sm text-gray-900">Concluído</label>
                                </div>
                                <div class="md:col-span-3">
                                    <label for="ensino_fundamental_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="ensino_fundamental_instituicao" id="ensino_fundamental_instituicao" 
                                           value="{{ old('ensino_fundamental_instituicao', $user->ensino_fundamental_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Ensino Médio -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Ensino Médio</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_medio_status" id="ensino_medio_nao" value="nao" 
                                           {{ (!old('ensino_medio_concluido', $user->ensino_medio_concluido ?? false) && !old('ensino_medio_cursando', $user->ensino_medio_cursando ?? false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_medio_nao" class="ml-2 block text-sm text-gray-900">Não cursou</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_medio_status" id="ensino_medio_cursando" value="cursando" 
                                           {{ old('ensino_medio_cursando', $user->ensino_medio_cursando ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_medio_cursando" class="ml-2 block text-sm text-gray-900">Cursando</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_medio_status" id="ensino_medio_concluido" value="concluido" 
                                           {{ old('ensino_medio_concluido', $user->ensino_medio_concluido ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_medio_concluido" class="ml-2 block text-sm text-gray-900">Concluído</label>
                                </div>
                                <div class="md:col-span-3">
                                    <label for="ensino_medio_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="ensino_medio_instituicao" id="ensino_medio_instituicao" 
                                           value="{{ old('ensino_medio_instituicao', $user->ensino_medio_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Ensino Técnico -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Ensino Técnico</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_tecnico_status" id="ensino_tecnico_nao" value="nao" 
                                           {{ (!old('ensino_tecnico_concluido', $user->ensino_tecnico_concluido ?? false) && !old('ensino_tecnico_cursando', $user->ensino_tecnico_cursando ?? false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_tecnico_nao" class="ml-2 block text-sm text-gray-900">Não cursou</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_tecnico_status" id="ensino_tecnico_cursando" value="cursando" 
                                           {{ old('ensino_tecnico_cursando', $user->ensino_tecnico_cursando ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_tecnico_cursando" class="ml-2 block text-sm text-gray-900">Cursando</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_tecnico_status" id="ensino_tecnico_concluido" value="concluido" 
                                           {{ old('ensino_tecnico_concluido', $user->ensino_tecnico_concluido ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_tecnico_concluido" class="ml-2 block text-sm text-gray-900">Concluído</label>
                                </div>
                                <div class="md:col-span-3">
                                    <label for="ensino_tecnico_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="ensino_tecnico_instituicao" id="ensino_tecnico_instituicao" 
                                           value="{{ old('ensino_tecnico_instituicao', $user->ensino_tecnico_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Ensino Superior -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Ensino Superior</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_superior_status" id="ensino_superior_nao" value="nao" 
                                           {{ (!old('ensino_superior_concluido', $user->ensino_superior_concluido ?? false) && !old('superior_cursando', $user->superior_cursando ?? false) && !old('superior_trancado', $user->superior_trancado ?? false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_superior_nao" class="ml-2 block text-sm text-gray-900">Não cursou</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_superior_status" id="superior_cursando" value="cursando" 
                                           {{ old('superior_cursando', $user->superior_cursando ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="superior_cursando" class="ml-2 block text-sm text-gray-900">Cursando</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_superior_status" id="superior_trancado" value="trancado" 
                                           {{ old('superior_trancado', $user->superior_trancado ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="superior_trancado" class="ml-2 block text-sm text-gray-900">Trancado</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ensino_superior_status" id="ensino_superior_concluido" value="concluido" 
                                           {{ old('ensino_superior_concluido', $user->ensino_superior_concluido ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="ensino_superior_concluido" class="ml-2 block text-sm text-gray-900">Concluído</label>
                                </div>
                                <div class="md:col-span-4">
                                    <label for="superior_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="superior_instituicao" id="superior_instituicao" 
                                           value="{{ old('superior_instituicao', $user->superior_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Pós-graduação -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Pós-graduação</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <input type="radio" name="pos_graduacao_status" id="pos_graduacao_nao" value="nao" 
                                           {{ (!old('pos_graduacao_concluido', $user->pos_graduacao_concluido ?? false) && !old('pos_graduacao_cursando', $user->pos_graduacao_cursando ?? false)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="pos_graduacao_nao" class="ml-2 block text-sm text-gray-900">Não cursou</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="pos_graduacao_status" id="pos_graduacao_cursando" value="cursando" 
                                           {{ old('pos_graduacao_cursando', $user->pos_graduacao_cursando ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="pos_graduacao_cursando" class="ml-2 block text-sm text-gray-900">Cursando</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="pos_graduacao_status" id="pos_graduacao_concluido" value="concluido" 
                                           {{ old('pos_graduacao_concluido', $user->pos_graduacao_concluido ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="pos_graduacao_concluido" class="ml-2 block text-sm text-gray-900">Concluído</label>
                                </div>
                                <div class="md:col-span-3">
                                    <label for="pos_graduacao_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="pos_graduacao_instituicao" id="pos_graduacao_instituicao" 
                                           value="{{ old('pos_graduacao_instituicao', $user->pos_graduacao_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cursos Extras -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-orange-100 text-orange-600 rounded-full p-2 mr-3">📚</span>
                        Cursos Extras
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Curso 1 -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Curso 1</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="curso_1" class="block text-sm font-medium text-gray-700 mb-2">Nome do Curso</label>
                                    <input type="text" name="curso_1" id="curso_1" 
                                           value="{{ old('curso_1', $user->curso_1 ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="curso_1_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="curso_1_instituicao" id="curso_1_instituicao" 
                                           value="{{ old('curso_1_instituicao', $user->curso_1_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Curso 2 -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Curso 2</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="curso_2" class="block text-sm font-medium text-gray-700 mb-2">Nome do Curso</label>
                                    <input type="text" name="curso_2" id="curso_2" 
                                           value="{{ old('curso_2', $user->curso_2 ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="curso_2_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="curso_2_instituicao" id="curso_2_instituicao" 
                                           value="{{ old('curso_2_instituicao', $user->curso_2_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Curso 3 -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Curso 3</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="curso_3" class="block text-sm font-medium text-gray-700 mb-2">Nome do Curso</label>
                                    <input type="text" name="curso_3" id="curso_3" 
                                           value="{{ old('curso_3', $user->curso_3 ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="curso_3_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Instituição</label>
                                    <input type="text" name="curso_3_instituicao" id="curso_3_instituicao" 
                                           value="{{ old('curso_3_instituicao', $user->curso_3_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Experiência Profissional -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 rounded-full p-2 mr-3">💼</span>
                        Experiência Profissional
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Situação Profissional -->
                        <div>
                            <label for="situacao_profissional" class="block text-sm font-medium text-gray-700 mb-2">Situação Profissional Atual</label>
                            <select name="situacao_profissional" id="situacao_profissional" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione...</option>
                                <option value="empregado" {{ old('situacao_profissional', $user->situacao_profissional ?? '') == 'empregado' ? 'selected' : '' }}>Empregado</option>
                                <option value="desempregado" {{ old('situacao_profissional', $user->situacao_profissional ?? '') == 'desempregado' ? 'selected' : '' }}>Desempregado</option>
                                <option value="autonomo" {{ old('situacao_profissional', $user->situacao_profissional ?? '') == 'autonomo' ? 'selected' : '' }}>Autônomo</option>
                                <option value="estudante" {{ old('situacao_profissional', $user->situacao_profissional ?? '') == 'estudante' ? 'selected' : '' }}>Estudante</option>
                                <option value="aposentado" {{ old('situacao_profissional', $user->situacao_profissional ?? '') == 'aposentado' ? 'selected' : '' }}>Aposentado</option>
                            </select>
                        </div>

                        <!-- Experiência 1 -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Experiência Profissional 1</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="experiencia_1_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Empresa/Instituição</label>
                                    <input type="text" name="experiencia_1_instituicao" id="experiencia_1_instituicao" 
                                           value="{{ old('experiencia_1_instituicao', $user->experiencia_1_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="experiencia_1_ano" class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                                    <input type="text" name="experiencia_1_ano" id="experiencia_1_ano" 
                                           value="{{ old('experiencia_1_ano', $user->experiencia_1_ano ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Ex: 2020-2022">
                                </div>
                                <div>
                                    <label for="experiencia_1_funcao" class="block text-sm font-medium text-gray-700 mb-2">Função/Cargo</label>
                                    <input type="text" name="experiencia_1_funcao" id="experiencia_1_funcao" 
                                           value="{{ old('experiencia_1_funcao', $user->experiencia_1_funcao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="experiencia_1_atividades" class="block text-sm font-medium text-gray-700 mb-2">Principais Atividades</label>
                                    <textarea name="experiencia_1_atividades" id="experiencia_1_atividades" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('experiencia_1_atividades', $user->experiencia_1_atividades ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Experiência 2 -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Experiência Profissional 2</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="experiencia_2_instituicao" class="block text-sm font-medium text-gray-700 mb-2">Empresa/Instituição</label>
                                    <input type="text" name="experiencia_2_instituicao" id="experiencia_2_instituicao" 
                                           value="{{ old('experiencia_2_instituicao', $user->experiencia_2_instituicao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="experiencia_2_ano" class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                                    <input type="text" name="experiencia_2_ano" id="experiencia_2_ano" 
                                           value="{{ old('experiencia_2_ano', $user->experiencia_2_ano ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Ex: 2018-2020">
                                </div>
                                <div>
                                    <label for="experiencia_2_funcao" class="block text-sm font-medium text-gray-700 mb-2">Função/Cargo</label>
                                    <input type="text" name="experiencia_2_funcao" id="experiencia_2_funcao" 
                                           value="{{ old('experiencia_2_funcao', $user->experiencia_2_funcao ?? '') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="experiencia_2_atividades" class="block text-sm font-medium text-gray-700 mb-2">Principais Atividades</label>
                                    <textarea name="experiencia_2_atividades" id="experiencia_2_atividades" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('experiencia_2_atividades', $user->experiencia_2_atividades ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Habilidades -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-green-100 text-green-600 rounded-full p-2 mr-3">⚡</span>
                        Habilidades
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nivel_ingles" class="block text-sm font-medium text-gray-700 mb-2">Nível de Inglês</label>
                            <select name="nivel_ingles" id="nivel_ingles" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione...</option>
                                <option value="basico" {{ old('nivel_ingles', $user->nivel_ingles ?? '') == 'basico' ? 'selected' : '' }}>Básico</option>
                                <option value="avancado" {{ old('nivel_ingles', $user->nivel_ingles ?? '') == 'avancado' ? 'selected' : '' }}>Avançado</option>
                                <option value="fluente" {{ old('nivel_ingles', $user->nivel_ingles ?? '') == 'fluente' ? 'selected' : '' }}>Fluente</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="hidden" name="desenvolveu_sistemas" value="0">
                                <input type="checkbox" name="desenvolveu_sistemas" id="desenvolveu_sistemas" value="1" 
                                       {{ old('desenvolveu_sistemas', $user->desenvolveu_sistemas ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="desenvolveu_sistemas" class="ml-2 block text-sm text-gray-900">Já desenvolveu sistemas</label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="ja_empreendeu" value="0">
                                <input type="checkbox" name="ja_empreendeu" id="ja_empreendeu" value="1" 
                                       {{ old('ja_empreendeu', $user->ja_empreendeu ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="ja_empreendeu" class="ml-2 block text-sm text-gray-900">Já empreendeu</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disponibilidade -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-teal-100 text-teal-600 rounded-full p-2 mr-3">🕒</span>
                        Disponibilidade
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Dias Disponíveis</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
                                    $diasLabels = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
                                    $diasSelecionados = old('disponibilidade_dias', $user->disponibilidade_dias ?? []);
                                    if (is_string($diasSelecionados)) {
                                        $diasSelecionados = json_decode($diasSelecionados, true) ?? [];
                                    }
                                @endphp
                                
                                @foreach($diasSemana as $index => $dia)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="disponibilidade_dias[]" id="dia_{{ $dia }}" value="{{ $dia }}" 
                                               {{ in_array($dia, $diasSelecionados) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="dia_{{ $dia }}" class="ml-2 block text-sm text-gray-900">{{ $diasLabels[$index] }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label for="disponibilidade_horario" class="block text-sm font-medium text-gray-700 mb-2">Horário Disponível</label>
                            <input type="text" name="disponibilidade_horario" id="disponibilidade_horario" 
                                   value="{{ old('disponibilidade_horario', $user->disponibilidade_horario ?? '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Ex: 08:00 às 17:00, Manhã, Tarde, Noite">
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        ← Cancelar
                    </a>
                    
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition-colors flex items-center space-x-2">
                        <span>{{ $isEdit ? '💾 Atualizar' : '➕ Criar' }}</span>
                        <span>Usuário</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle quantidade de filhos
    const temFilhosCheckbox = document.getElementById('tem_filhos');
    const quantidadeContainer = document.getElementById('quantidade_filhos_container');
    
    temFilhosCheckbox.addEventListener('change', function() {
        quantidadeContainer.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            document.getElementById('quantidade_filhos').value = '';
        }
    });

    // Toggle campos de saúde
    const toggleHealthField = (checkboxId, containerId) => {
        const checkbox = document.getElementById(checkboxId);
        const container = document.getElementById(containerId);
        
        if (checkbox && container) {
            checkbox.addEventListener('change', function() {
                container.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    const textarea = container.querySelector('textarea');
                    if (textarea) textarea.value = '';
                }
            });
        }
    };

    // Aplicar toggle para todos os campos de saúde
    toggleHealthField('tem_deficiencia', 'descricao_deficiencia_container');
    toggleHealthField('tem_condicao_saude', 'descricao_saude_container');
    toggleHealthField('tem_alergia', 'descricao_alergia_container');
    toggleHealthField('usa_medicamento', 'qual_medicamento_container');

    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
    }

    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Máscara para telefone (aplicar para telefone principal e de urgência)
    const applyPhoneMask = (inputId) => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value;
            });
        }
    };

    applyPhoneMask('telefone');
    applyPhoneMask('urgencia_telefone_contato');

    // Máscara para RG
    const rgInput = document.getElementById('rg');
    if (rgInput) {
        rgInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1})$/, '$1-$2');
            e.target.value = value;
        });
    }

    // Validação de idade baseada na data de nascimento
    const dataNascimento = document.getElementById('data_nascimento');
    const idadeInput = document.getElementById('idade');
    
    if (dataNascimento && idadeInput) {
        dataNascimento.addEventListener('change', function() {
            if (this.value) {
                const hoje = new Date();
                const nascimento = new Date(this.value);
                let idade = hoje.getFullYear() - nascimento.getFullYear();
                const mes = hoje.getMonth() - nascimento.getMonth();
                
                if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
                    idade--;
                }
                
                if (idade >= 0 && idade <= 120) {
                    idadeInput.value = idade;
                }
            }
        });
    }

    // Validação de formulário antes do envio
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validar campos obrigatórios
            const requiredFields = ['name', 'email', 'role'];
            const isEdit = document.querySelector('input[name="_method"][value="PATCH"]') !== null;
            
            if (!isEdit) {
                requiredFields.push('password');
            }
            
            let hasErrors = false;
            
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && !field.value.trim()) {
                    field.classList.add('border-red-500');
                    hasErrors = true;
                } else if (field) {
                    field.classList.remove('border-red-500');
                }
            });
            
            // Validar confirmação de senha
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            
            if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
                passwordConfirmation.classList.add('border-red-500');
                alert('As senhas não conferem!');
                hasErrors = true;
            } else if (passwordConfirmation) {
                passwordConfirmation.classList.remove('border-red-500');
            }
            
            if (hasErrors) {
                e.preventDefault();
                document.querySelector('.max-w-4xl').scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Radio buttons de educação já garantem exclusividade, não precisa de JavaScript adicional

    // Contador de caracteres para textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        if (maxLength) {
            const counter = document.createElement('div');
            counter.className = 'text-xs text-gray-500 mt-1 text-right';
            counter.textContent = `0/${maxLength}`;
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length}/${maxLength}`;
                if (this.value.length > maxLength * 0.9) {
                    counter.className = 'text-xs text-orange-500 mt-1 text-right';
                } else {
                    counter.className = 'text-xs text-gray-500 mt-1 text-right';
                }
            });
        }
    });
});
</script>