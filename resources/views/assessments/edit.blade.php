<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <x-slot name="header">
        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ✏️
                        </span>
                        Editar Avaliação
                    </h2>
                    <p class="text-yellow-100 mt-1">{{ $assessment->title }}</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('assessments.show', $assessment) }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>👁️</span>
                        <span>Visualizar</span>
                    </a>
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
                    <!-- Mensagens de Erro/Sucesso -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-green-400 text-2xl mr-3">✅</div>
                                <div class="text-green-800">{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-red-400 text-2xl mr-3">❌</div>
                                <div class="text-red-800">{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="text-red-400 text-2xl mr-3">⚠️</div>
                                <div>
                                    <h4 class="text-red-800 font-medium mb-2">Erro de Validação:</h4>
                                    <ul class="text-red-700 text-sm space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form id="assessmentForm" method="POST" action="{{ route('assessments.update', $assessment) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Informações Básicas -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Informações Básicas</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="title" :value="__('Título da Avaliação')" />
                                    <x-text-input id="title" name="title" type="text" 
                                                class="mt-1 block w-full" 
                                                :value="old('title', $assessment->title)" 
                                                required autofocus 
                                                placeholder="Ex: Prova de Matemática - 1º Bimestre" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="description" :value="__('Descrição')" />
                                    <textarea id="description" name="description" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3" 
                                            placeholder="Descreva o conteúdo e objetivos da avaliação...">{{ old('description', $assessment->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="subject_id" :value="__('Disciplina')" />
                                    <select id="subject_id" name="subject_id" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                            required>
                                        <option value="">Selecione uma disciplina</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $assessment->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                                :value="old('duration_minutes', $assessment->duration_minutes)" 
                                                min="1" max="480" required />
                                    <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="max_score" :value="__('Pontuação Máxima')" />
                                    <x-text-input id="max_score" name="max_score" type="number" 
                                                class="mt-1 block w-full" 
                                                :value="old('max_score', $assessment->max_score)" 
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
                                                :value="old('opens_at', $assessment->opens_at->format('Y-m-d\TH:i'))" 
                                                required />
                                    <x-input-error :messages="$errors->get('opens_at')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="closes_at" :value="__('Data/Hora de Fechamento')" />
                                    <x-text-input id="closes_at" name="closes_at" type="datetime-local" 
                                                class="mt-1 block w-full" 
                                                :value="old('closes_at', $assessment->closes_at->format('Y-m-d\TH:i'))" 
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
                                                <input type="checkbox" name="class_ids[]" value="{{ $class->id }}" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ in_array($class->id, old('class_ids', $assessment->classes->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                                    Você precisa criar pelo menos uma turma.
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
                            
                            <x-input-error :messages="$errors->get('class_ids')" class="mt-2" />
                        </div>


                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('assessments.show', $assessment) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                ❌ Cancelar
                            </a>
                            
                            <button type="submit" id="saveButton"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors"
                                    onclick="console.log('🔥 BOTÃO CLICADO!'); return true;">
                                💾 Salvar Alterações
                            </button>
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para alternar seleção de todas as turmas
        function toggleAllClasses() {
            const checkboxes = document.querySelectorAll('input[name="class_ids[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }
        
        // Função de teste para debug do formulário
        function testFormSubmission() {
            const form = document.getElementById('assessmentForm');
            const formData = new FormData(form);
            
            console.log('=== TESTE DO FORMULÁRIO ===');
            console.log('Action:', form.action);
            console.log('Method:', form.method);
            console.log('Dados do formulário:');
            
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Verificar se pelo menos uma turma está selecionada
            const selectedClasses = formData.getAll('class_ids[]');
            console.log('Turmas selecionadas:', selectedClasses.length);
            
            if (selectedClasses.length === 0) {
                alert('❌ Nenhuma turma selecionada! Selecione pelo menos uma turma.');
                return;
            }
            
            alert('✅ Formulário OK!\nTurmas: ' + selectedClasses.length + '\nAction: ' + form.action);
        }
        // Aguardar o DOM carregar completamente
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado, inicializando scripts...');
            
            // Teste simples: adicionar apenas log sem interferir
            const mainForm = document.getElementById('assessmentForm');
            if (mainForm) {
                console.log('✅ Formulário encontrado:', mainForm);
                console.log('✅ Action:', mainForm.action);
                console.log('✅ Method:', mainForm.method);
                
                // Teste: verificar se o formulário tem outros listeners
                const listeners = getEventListeners ? getEventListeners(mainForm) : 'N/A';
                console.log('✅ Event listeners no formulário:', listeners);
            }
            
            // Função para alternar todas as turmas
            window.toggleAllClasses = function() {
                const checkboxes = document.querySelectorAll('input[name="class_ids[]"]');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
            };


        });
        
    </script>
</x-app-layout>