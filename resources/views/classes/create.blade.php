<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-emerald-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ➕
                        </span>
                        Criar Nova Turma
                    </h2>
                    <p class="text-emerald-100 mt-1">Configure uma nova turma para organizar seus alunos e avaliações</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('classes.index') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Formulário Principal -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-emerald-100 text-emerald-600 rounded-lg p-2 mr-3">📋</span>
                        Informações da Turma
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Preencha os dados básicos para criar sua nova turma</p>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ route('classes.store') }}" class="space-y-8">
                        @csrf

                        <!-- Nome da Turma -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <span class="bg-blue-100 text-blue-600 rounded-lg p-2">🏫</span>
                                <x-input-label for="name" :value="__('Nome da Turma')" class="text-lg font-semibold" />
                            </div>
                            <x-text-input id="name" name="name" type="text" 
                                         class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200" 
                                         :value="old('name')" 
                                         required autofocus 
                                         placeholder="Ex: 3º Ano A, Turma de Matemática 2024..." />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            <p class="text-sm text-gray-500">💡 Use um nome claro e descritivo para identificar facilmente a turma</p>
                        </div>

                        <!-- Descrição -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <span class="bg-green-100 text-green-600 rounded-lg p-2">📄</span>
                                <x-input-label for="description" :value="__('Descrição (Opcional)')" class="text-lg font-semibold" />
                            </div>
                            <textarea id="description" name="description" 
                                     class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                                     rows="4" 
                                     placeholder="Descrição detalhada da turma, objetivos, período letivo, etc...">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            <p class="text-sm text-gray-500">💡 Adicione informações sobre objetivos, cronograma ou características especiais da turma</p>
                        </div>

                        <!-- Professor Responsável (apenas para admin) -->
                        @if(auth()->user()->isAdmin())
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <span class="bg-purple-100 text-purple-600 rounded-lg p-2">👨‍🏫</span>
                                    <x-input-label for="teacher_id" :value="__('Professor Responsável')" class="text-lg font-semibold" />
                                </div>
                                <select id="teacher_id" name="teacher_id" 
                                        class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                                        required>
                                    <option value="">Selecione um professor</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
                                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} ({{ $teacher->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('teacher_id')" />
                                <p class="text-sm text-gray-500">💡 O professor selecionado será responsável por gerenciar esta turma</p>
                            </div>
                        @else
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-xl p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="bg-blue-100 rounded-lg p-3">
                                        <span class="text-2xl">👨‍🏫</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-blue-800 mb-2">
                                            Professor Responsável
                                        </h3>
                                        <div class="text-blue-700 space-y-1">
                                            <p>Você será automaticamente definido como professor responsável por esta turma.</p>
                                            <p class="font-semibold text-blue-900 bg-blue-100 px-3 py-1 rounded-lg inline-block">
                                                {{ auth()->user()->name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Botões -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                <span class="mr-2">💡</span>
                                Após criar, você poderá adicionar alunos e configurar avaliações
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('classes.index') }}" 
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2">
                                    <span>❌</span>
                                    <span>Cancelar</span>
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg transform hover:scale-105">
                                    <span>➕</span>
                                    <span>Criar Turma</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cards de Dicas e Próximos Passos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Dicas -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <span class="bg-yellow-100 text-yellow-600 rounded-lg p-2 mr-3">💡</span>
                            Dicas para Criar uma Turma
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">1</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Nome Descritivo</h4>
                                    <p class="text-sm text-gray-600">Use nomes que identifiquem claramente a turma, incluindo ano letivo ou disciplina</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="bg-green-100 text-green-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">2</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Descrição Completa</h4>
                                    <p class="text-sm text-gray-600">Adicione objetivos, cronograma e informações importantes na descrição</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="bg-purple-100 text-purple-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">3</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Organização</h4>
                                    <p class="text-sm text-gray-600">Mantenha um padrão de nomenclatura para facilitar a organização</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Próximos Passos -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <span class="bg-emerald-100 text-emerald-600 rounded-lg p-2 mr-3">🚀</span>
                            Próximos Passos
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                <span class="text-2xl">👥</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Adicionar Alunos</h4>
                                    <p class="text-sm text-gray-600">Matricule alunos na turma para começar as atividades</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                                <span class="text-2xl">📝</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Criar Avaliações</h4>
                                    <p class="text-sm text-gray-600">Configure provas e exercícios para a turma</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                                <span class="text-2xl">📊</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Acompanhar Progresso</h4>
                                    <p class="text-sm text-gray-600">Monitore o desempenho dos alunos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>