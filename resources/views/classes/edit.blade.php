<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-amber-600 to-orange-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ✏️
                        </span>
                        Editar Turma
                    </h2>
                    <p class="text-amber-100 mt-1">{{ $class->name }} • Atualize as informações da turma</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('classes.show', $class) }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Formulário Principal -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-amber-100 text-amber-600 rounded-lg p-2 mr-3">📝</span>
                        Editar Informações da Turma
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Atualize os dados da turma conforme necessário</p>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ route('classes.update', $class) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Nome da Turma -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <span class="bg-blue-100 text-blue-600 rounded-lg p-2">🏫</span>
                                <x-input-label for="name" :value="__('Nome da Turma')" class="text-lg font-semibold" />
                            </div>
                            <x-text-input id="name" name="name" type="text" 
                                         class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200" 
                                         :value="old('name', $class->name)" 
                                         required autofocus 
                                         placeholder="Ex: 3º Ano A, Turma de Matemática 2024..." />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            <p class="text-sm text-gray-500">💡 Mantenha um nome claro e descritivo para a turma</p>
                        </div>

                        <!-- Descrição -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <span class="bg-green-100 text-green-600 rounded-lg p-2">📄</span>
                                <x-input-label for="description" :value="__('Descrição (Opcional)')" class="text-lg font-semibold" />
                            </div>
                            <textarea id="description" name="description" 
                                     class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                                     rows="4" 
                                     placeholder="Descrição detalhada da turma, objetivos, período letivo, etc...">{{ old('description', $class->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            <p class="text-sm text-gray-500">💡 Atualize informações sobre objetivos ou características da turma</p>
                        </div>

                        <!-- Professor Responsável (apenas para admin) -->
                        @if(auth()->user()->isAdmin())
                            <div>
                                <x-input-label for="teacher_id" :value="__('Professor Responsável')" />
                                <select id="teacher_id" name="teacher_id" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        required>
                                    <option value="">Selecione um professor</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
                                                {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} ({{ $teacher->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('teacher_id')" />
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">
                                            Professor Responsável
                                        </h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>Você é o professor responsável por esta turma.</p>
                                            <p class="font-medium">{{ $class->teacher->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Estatísticas da Turma -->
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="bg-gray-100 text-gray-600 rounded-lg p-2 mr-3">📊</span>
                                Estatísticas da Turma
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $class->students->count() }}</div>
                                    <div class="text-sm text-gray-600">Alunos Matriculados</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $class->assessments->count() }}</div>
                                    <div class="text-sm text-gray-600">Avaliações Criadas</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center">
                                    <div class="text-sm font-bold text-purple-600">{{ $class->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-600">Data de Criação</div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                <span class="mr-2">💡</span>
                                As alterações serão aplicadas imediatamente
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('classes.show', $class) }}" 
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2">
                                    <span>❌</span>
                                    <span>Cancelar</span>
                                </a>
                                <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg transform hover:scale-105">
                                    <span>💾</span>
                                    <span>Salvar Alterações</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ações Perigosas (apenas para admin) -->
            @if(auth()->user()->isAdmin())
                <div class="bg-white rounded-2xl shadow-xl border border-red-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-red-200">
                        <h3 class="text-lg font-bold text-red-800 flex items-center">
                            <span class="bg-red-100 text-red-600 rounded-lg p-2 mr-3">⚠️</span>
                            Zona de Perigo
                        </h3>
                        <p class="text-red-600 text-sm mt-1">Ações irreversíveis que afetam permanentemente esta turma</p>
                    </div>
                    <div class="p-6">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                            <div class="flex items-start space-x-4">
                                <div class="bg-red-100 rounded-lg p-3">
                                    <span class="text-2xl">🗑️</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-red-900 mb-2">Excluir Turma</h4>
                                    <p class="text-red-700 mb-4">
                                        Esta ação removerá permanentemente a turma e todos os seus dados. 
                                        Esta operação não pode ser desfeita.
                                    </p>
                                    <div class="space-y-2 text-sm text-red-600 mb-4">
                                        <div class="flex items-center">
                                            <span class="mr-2">•</span>
                                            <span>{{ $class->students->count() }} aluno(s) matriculado(s)</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="mr-2">•</span>
                                            <span>{{ $class->assessments->count() }} avaliação(ões) associada(s)</span>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('classes.destroy', $class) }}" 
                                          class="inline" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 shadow-lg">
                                            <span>🗑️</span>
                                            <span>Excluir Turma Permanentemente</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            const studentsCount = {{ $class->students->count() }};
            const assessmentsCount = {{ $class->assessments->count() }};
            const className = "{{ $class->name }}";
            
            let message = `Tem certeza que deseja excluir a turma "${className}"?`;
            
            if (studentsCount > 0) {
                alert(`Não é possível excluir esta turma pois ela possui ${studentsCount} aluno(s) matriculado(s). Remova os alunos primeiro.`);
                return false;
            }
            
            if (assessmentsCount > 0) {
                alert(`Não é possível excluir esta turma pois ela possui ${assessmentsCount} avaliação(ões) associada(s).`);
                return false;
            }
            
            message += '\n\n⚠️ Esta ação não pode ser desfeita!';
            message += '\n• A turma será permanentemente excluída';
            message += '\n• Todos os relacionamentos serão removidos';
            
            if (confirm(message)) {
                return confirm('Confirma DEFINITIVAMENTE a exclusão da turma?');
            }
            
            return false;
        }
    </script>
</x-app-layout>