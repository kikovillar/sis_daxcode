<x-app-layout>
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

                        <!-- Status da Avaliação -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Status e Controles</h3>
                            
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">
                                        <strong>Status atual:</strong> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($assessment->status === 'published') bg-green-100 text-green-800
                                            @elseif($assessment->status === 'draft') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($assessment->status === 'published') ✅ Publicada
                                            @elseif($assessment->status === 'draft') 📝 Rascunho
                                            @else 🔒 Fechada @endif
                                        </span>
                                    </p>
                                    
                                    <div class="mt-2 text-sm text-gray-600">
                                        <strong>Tentativas de alunos:</strong> {{ $assessment->studentAssessments()->count() }}
                                    </div>
                                    
                                    @if($assessment->studentAssessments()->exists())
                                        <p class="text-sm text-orange-600 mt-2">
                                            ⚠️ Esta avaliação já possui {{ $assessment->studentAssessments()->count() }} tentativa(s) de aluno(s). 
                                            Alterações em pontuação, questões e datas podem afetar os resultados.
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Ações Rápidas -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @if($assessment->status === 'draft' && $assessment->questions()->count() > 0)
                                        <form method="POST" action="{{ route('teacher.assessment.publish', $assessment) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                📢 Publicar Avaliação
                                            </button>
                                        </form>
                                    @elseif($assessment->status === 'published')
                                        <form method="POST" action="{{ route('teacher.assessment.close', $assessment) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                                    onclick="return confirm('Tem certeza que deseja fechar esta avaliação? Alunos não poderão mais fazê-la.')">
                                                🔒 Fechar Avaliação
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('assessments.show', $assessment) }}" 
                                       class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                        👁️ Visualizar
                                    </a>
                                    
                                    @if($assessment->studentAssessments()->count() === 0)
                                        <button onclick="confirmDeleteFromEdit()" 
                                                class="w-full bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                                            🗑️ Excluir Avaliação
                                        </button>
                                        
                                        <form id="deleteFormEdit" method="POST" action="{{ route('assessments.destroy', $assessment) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <span class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded text-center cursor-not-allowed block" 
                                              title="Não é possível excluir avaliação com tentativas de alunos">
                                            🔒 Protegida
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Questões -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">❓ Questões ({{ $assessment->questions->count() }})</h3>
                            
                            @if($assessment->questions->count() > 0)
                                <div class="space-y-2">
                                    @foreach($assessment->questions as $question)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <span class="font-medium">{{ $question->title }}</span>
                                                <span class="text-sm text-gray-500 ml-2">({{ $question->points }} pts)</span>
                                            </div>
                                            <span class="text-xs text-gray-400">Ordem: {{ $question->pivot->order }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">Nenhuma questão adicionada ainda.</p>
                            @endif
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('assessments.show', $assessment) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                ❌ Cancelar
                            </a>
                            
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="console.log('Botão clicado! Enviando formulário...');">
                                💾 Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Gerenciamento de Questões -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-purple-100 text-purple-600 rounded-lg p-2 mr-3">❓</span>
                        Gerenciar Questões
                        <span class="ml-2 bg-gray-100 text-gray-600 text-sm px-2 py-1 rounded-full">
                            {{ $assessment->questions->count() }} questões
                        </span>
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Questões Atuais -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">📋</span>
                                Questões na Avaliação
                            </h4>
                            
                            @if($assessment->questions->count() > 0)
                                <div class="space-y-3 max-h-96 overflow-y-auto">
                                    @foreach($assessment->questions as $question)
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900 mb-1">{{ $question->title }}</h5>
                                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($question->content, 100) }}</p>
                                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                        <span>📚 {{ $question->subject->name }}</span>
                                                        <span>🏆 {{ $question->points }} pts</span>
                                                        <span>
                                                            @if($question->type === 'multiple_choice') 🔘 Múltipla Escolha
                                                            @elseif($question->type === 'true_false') ✅ V/F
                                                            @else 📝 Dissertativa @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('teacher.assessment.remove-question', [$assessment, $question]) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs"
                                                            onclick="return confirm('Remover esta questão da avaliação?')">
                                                        🗑️ Remover
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <div class="text-4xl mb-2">❓</div>
                                    <p class="text-gray-600">Nenhuma questão adicionada ainda</p>
                                </div>
                            @endif
                        </div>

                        <!-- Adicionar Questões -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="bg-green-100 text-green-600 rounded-lg p-2 mr-3">➕</span>
                                Adicionar Questões
                            </h4>
                            
                            @if($availableQuestions->count() > 0)
                                <form id="addQuestionsForm" method="POST" action="{{ route('teacher.assessment.add-questions', $assessment) }}">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <input type="text" id="questionSearch" placeholder="🔍 Buscar questões..." 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="space-y-3 max-h-96 overflow-y-auto" id="questionsContainer">
                                        @foreach($availableQuestions as $question)
                                            <div class="question-item bg-white border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                                <label class="flex items-start cursor-pointer">
                                                    <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" 
                                                           class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    <div class="ml-3 flex-1">
                                                        <h5 class="font-medium text-gray-900 mb-1 question-title">{{ $question->title }}</h5>
                                                        <p class="text-sm text-gray-600 mb-2 question-content">{{ Str::limit($question->content, 100) }}</p>
                                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                            <span class="question-subject">📚 {{ $question->subject->name }}</span>
                                                            <span>🏆 {{ $question->points }} pts</span>
                                                            <span>
                                                                @if($question->type === 'multiple_choice') 🔘 Múltipla Escolha
                                                                @elseif($question->type === 'true_false') ✅ V/F
                                                                @else 📝 Dissertativa @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-4 flex justify-between items-center">
                                        <div class="space-x-2">
                                            <button type="button" onclick="selectAllQuestions()" 
                                                    class="text-sm text-blue-600 hover:text-blue-800">
                                                ✅ Selecionar Todas
                                            </button>
                                            <button type="button" onclick="deselectAllQuestions()" 
                                                    class="text-sm text-gray-600 hover:text-gray-800">
                                                ❌ Desmarcar Todas
                                            </button>
                                        </div>
                                        
                                        <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            ➕ Adicionar Selecionadas
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p class="text-gray-600 mb-4">Todas as questões já foram adicionadas</p>
                                    <a href="{{ route('questions.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        ➕ Criar Nova Questão
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para selecionar/desmarcar todas as turmas
        function toggleAllClasses() {
            const checkboxes = document.querySelectorAll('input[name="class_ids[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        // Função para selecionar todas as questões
        function selectAllQuestions() {
            document.querySelectorAll('input[name="question_ids[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        // Função para desmarcar todas as questões
        function deselectAllQuestions() {
            document.querySelectorAll('input[name="question_ids[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        // Busca de questões
        document.getElementById('questionSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const questionItems = document.querySelectorAll('.question-item');
            
            questionItems.forEach(item => {
                const title = item.querySelector('.question-title').textContent.toLowerCase();
                const content = item.querySelector('.question-content').textContent.toLowerCase();
                const subject = item.querySelector('.question-subject').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm) || subject.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Envio do formulário de adicionar questões via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const addQuestionsForm = document.getElementById('addQuestionsForm');
            if (addQuestionsForm) {
                addQuestionsForm.addEventListener('submit', function(e) {
                    e.preventDefault();
            
            const formData = new FormData(this);
            const selectedQuestions = formData.getAll('question_ids[]');
            
            if (selectedQuestions.length === 0) {
                alert('⚠️ Selecione pelo menos uma questão para adicionar!');
                return;
            }
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    location.reload(); // Recarregar para atualizar as listas
                } else {
                    alert('❌ ' + data.message);
                }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Erro ao adicionar questões. Tente novamente.');
                });
            }
        });
    </script>

    <script>
        // Auto-ajustar data de fechamento quando data de abertura mudar
        document.getElementById('opens_at').addEventListener('change', function() {
            const opensAt = new Date(this.value);
            const closesAtInput = document.getElementById('closes_at');
            
            // Se a data de fechamento for anterior à nova data de abertura, ajustar
            if (new Date(closesAtInput.value) <= opensAt) {
                const closesAt = new Date(opensAt.getTime() + (7 * 24 * 60 * 60 * 1000)); // +7 dias
                closesAtInput.value = closesAt.toISOString().slice(0, 16);
            }
        });
        
        function confirmDeleteFromEdit() {
            const assessmentTitle = "{{ $assessment->title }}";
            
            let message = `Tem certeza que deseja excluir a avaliação "${assessmentTitle}"?`;
            message += '\n\n⚠️ Esta ação não pode ser desfeita!';
            message += '\n• Todas as questões serão removidas da avaliação';
            message += '\n• As turmas serão desvinculadas';
            message += '\n• A avaliação será permanentemente excluída';
            message += '\n• Você será redirecionado para a lista de avaliações';
            
            if (confirm(message)) {
                if (confirm('Confirma DEFINITIVAMENTE a exclusão da avaliação?')) {
                    document.getElementById('deleteFormEdit').submit();
                }
            }
        }
        
        // Validação antes de salvar
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('assessmentForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Event listener do formulário disparado!');
                    
                    const hasStudentAttempts = {{ $assessment->studentAssessments()->count() }};
                    console.log('Tentativas de alunos:', hasStudentAttempts);
                    
                    if (hasStudentAttempts > 0) {
                        const opensAt = new Date(document.getElementById('opens_at').value);
                        const closesAt = new Date(document.getElementById('closes_at').value);
                        const originalOpensAt = new Date('{{ $assessment->opens_at->format('Y-m-d\TH:i') }}');
                        const originalClosesAt = new Date('{{ $assessment->closes_at->format('Y-m-d\TH:i') }}');
                        
                        if (opensAt.getTime() !== originalOpensAt.getTime() || closesAt.getTime() !== originalClosesAt.getTime()) {
                            if (!confirm('⚠️ Esta avaliação possui tentativas de alunos. Alterar as datas pode afetar os resultados. Continuar?')) {
                                console.log('Usuário cancelou a alteração');
                                e.preventDefault();
                                return false;
                            }
                        }
                    }
                    
                    console.log('Formulário será enviado normalmente');
                });
            } else {
                console.error('Formulário assessmentForm não encontrado!');
            }
        });
    </script>
</x-app-layout>