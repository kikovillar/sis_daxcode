<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ➕
                        </span>
                        Nova Avaliação
                    </h2>
                    <p class="text-green-100 mt-1">Crie uma nova avaliação para suas turmas</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('assessments.index') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('assessments.store') }}" class="space-y-6">
                        @csrf

                        <!-- Informações Básicas -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Informações Básicas</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="title" :value="__('Título da Avaliação')" />
                                    <x-text-input id="title" name="title" type="text" 
                                                class="mt-1 block w-full" 
                                                :value="old('title')" 
                                                required autofocus 
                                                placeholder="Ex: Prova de Matemática - 1º Bimestre" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="description" :value="__('Descrição')" />
                                    <textarea id="description" name="description" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3" 
                                            placeholder="Descreva o conteúdo e objetivos da avaliação...">{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="subject_id" :value="__('Disciplina')" />
                                    <select id="subject_id" name="subject_id" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                            required>
                                        <option value="">Selecione uma disciplina</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="duration_minutes" :value="__('Duração (minutos)')" />
                                    <x-text-input id="duration_minutes" name="duration_minutes" type="number" 
                                                class="mt-1 block w-full" 
                                                :value="old('duration_minutes', 60)" 
                                                min="1" max="480" required />
                                    <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="max_score" :value="__('Pontuação Máxima')" />
                                    <x-text-input id="max_score" name="max_score" type="number" 
                                                class="mt-1 block w-full" 
                                                :value="old('max_score', 100)" 
                                                min="1" step="0.1" required />
                                    <x-input-error :messages="$errors->get('max_score')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Período de Disponibilidade -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📅 Período de Disponibilidade</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="opens_at" :value="__('Data/Hora de Abertura')" />
                                    <x-text-input id="opens_at" name="opens_at" type="datetime-local" 
                                                class="mt-1 block w-full" 
                                                :value="old('opens_at')" 
                                                required />
                                    <x-input-error :messages="$errors->get('opens_at')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="closes_at" :value="__('Data/Hora de Fechamento')" />
                                    <x-text-input id="closes_at" name="closes_at" type="datetime-local" 
                                                class="mt-1 block w-full" 
                                                :value="old('closes_at')" 
                                                required />
                                    <x-input-error :messages="$errors->get('closes_at')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Turmas -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">👥 Turmas</h3>
                            
                            @if($classes->count() > 0)
                                <div class="space-y-3">
                                    <p class="text-sm text-gray-600 mb-3">
                                        Selecione as turmas que terão acesso a esta avaliação:
                                    </p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($classes as $class)
                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                                <input type="checkbox" name="classes[]" value="{{ $class->id }}" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ in_array($class->id, old('classes', [])) ? 'checked' : '' }}>
                                                <div class="ml-3 flex-1">
                                                    <div class="font-medium text-gray-900">{{ $class->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        👥 {{ $class->students->count() }} alunos
                                                        @if(auth()->user()->isAdmin() && $class->teacher)
                                                            • 👨‍🏫 {{ $class->teacher->name }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-3">
                                        <button type="button" onclick="toggleAllClasses()" 
                                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            🔄 Selecionar/Desmarcar Todas
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="text-yellow-400 text-2xl mr-3">⚠️</div>
                                        <div>
                                            <h4 class="text-yellow-800 font-medium">Nenhuma turma disponível</h4>
                                            <p class="text-yellow-700 text-sm mt-1">
                                                @if(auth()->user()->isTeacher())
                                                    Você precisa criar pelo menos uma turma antes de criar uma avaliação.
                                                    <a href="{{ route('classes.create') }}" class="font-medium underline hover:no-underline">
                                                        Criar turma agora
                                                    </a>
                                                @else
                                                    Não há turmas cadastradas no sistema.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <x-input-error :messages="$errors->get('classes')" class="mt-2" />
                        </div>

                        <!-- Configurações Avançadas -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">⚙️ Configurações</h3>
                            
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="shuffle_questions" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ old('shuffle_questions') ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">
                                        🔀 Embaralhar ordem das questões
                                    </span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="show_results_immediately" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ old('show_results_immediately') ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">
                                        📊 Mostrar resultados imediatamente após finalização
                                    </span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_review" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ old('allow_review', true) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">
                                        👁️ Permitir revisão das respostas
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('assessments.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                ❌ Cancelar
                            </a>
                            
                            <button type="submit" name="action" value="draft"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                💾 Salvar como Rascunho
                            </button>
                            
                            <button type="submit" name="action" value="publish"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                📢 Criar e Publicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-ajustar data de fechamento quando data de abertura mudar
        document.getElementById('opens_at').addEventListener('change', function() {
            const opensAt = new Date(this.value);
            const closesAt = new Date(opensAt.getTime() + (7 * 24 * 60 * 60 * 1000)); // +7 dias
            
            const closesAtInput = document.getElementById('closes_at');
            closesAtInput.value = closesAt.toISOString().slice(0, 16);
        });

        // Função para selecionar/desmarcar todas as turmas
        function toggleAllClasses() {
            const checkboxes = document.querySelectorAll('input[name="classes[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        // Validação antes do envio
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedClasses = document.querySelectorAll('input[name="classes[]"]:checked');
            
            if (selectedClasses.length === 0) {
                e.preventDefault();
                alert('⚠️ Selecione pelo menos uma turma para a avaliação!');
                return false;
            }
            
            // Confirmação para publicação direta
            if (e.submitter && e.submitter.value === 'publish') {
                if (!confirm('🚀 Tem certeza que deseja criar e publicar esta avaliação imediatamente?\n\nEla ficará disponível para os alunos das turmas selecionadas.')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
</x-app-layout>