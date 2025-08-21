<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $assessment->title }}
            </h2>
            
            <div class="flex space-x-2">
                @if(auth()->user()->isTeacher() && $assessment->teacher_id === auth()->id())
                    <a href="{{ route('assessments.edit', $assessment) }}" 
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        ✏️ Editar
                    </a>
                    
                    <!-- Botão de Relatório -->
                    <a href="{{ route('assessments.report', $assessment) }}" 
                       class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        📊 Relatório
                    </a>
                    
                    <!-- Botão de Configurações Avançadas -->
                    <a href="{{ route('assessments.advanced', $assessment) }}" 
                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        ⚙️ Avançado
                    </a>
                    
                    <!-- Botão de Backup -->
                    <a href="{{ route('assessments.backup', $assessment) }}" 
                       class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded"
                       download>
                        💾 Backup
                    </a>
                    
                    <!-- Botão de Duplicar -->
                    <form method="POST" action="{{ route('assessments.duplicate', $assessment) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Deseja duplicar esta avaliação? Uma cópia será criada como rascunho.')">
                            📋 Duplicar
                        </button>
                    </form>
                    
                    <!-- Botão de Exportar -->
                    <a href="{{ route('assessments.export', $assessment) }}" 
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                       download>
                        📤 Exportar
                    </a>
                    
                    <!-- Botão de Excluir -->
                    <button onclick="confirmDelete()" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        🗑️ Excluir
                    </button>
                    
                    <!-- Formulário de Exclusão (hidden) -->
                    <form id="deleteForm" method="POST" action="{{ route('assessments.destroy', $assessment) }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
                
                <a href="{{ route('assessments.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Informações da Avaliação -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $assessment->status }}</div>
                            <div class="text-sm text-gray-600">Status</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $assessment->duration_minutes }}min</div>
                            <div class="text-sm text-gray-600">Duração</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $assessment->max_score }}</div>
                            <div class="text-sm text-gray-600">Pontuação Máxima</div>
                        </div>
                        
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">{{ $assessment->questions->count() }}</div>
                            <div class="text-sm text-gray-600">Questões</div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Descrição</h3>
                        <p class="text-gray-600">{{ $assessment->description ?: 'Nenhuma descrição fornecida.' }}</p>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900">📚 Disciplina</h4>
                            <p class="text-gray-600">{{ $assessment->subject->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">👨‍🏫 Professor</h4>
                            <p class="text-gray-600">{{ $assessment->teacher->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">📅 Período</h4>
                            <p class="text-gray-600">
                                {{ $assessment->opens_at->format('d/m/Y H:i') }} até 
                                {{ $assessment->closes_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900">👥 Turmas</h4>
                            <p class="text-gray-600">
                                @foreach($assessment->classes as $class)
                                    {{ $class->name }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questões -->
            @if(auth()->user()->isTeacher() && $assessment->teacher_id === auth()->id())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">❓ Questões ({{ $assessment->questions->count() }})</h3>
                            
                            <button onclick="openQuestionModal()" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Adicionar Questões
                            </button>
                        </div>
                        
                        @if($assessment->questions->count() > 0)
                            <div class="space-y-4">
                                @foreach($assessment->questions->sortBy('pivot.order') as $question)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                                                        Questão {{ $loop->iteration }}
                                                    </span>
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                                        {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                    </span>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                                        {{ $question->points }} pts
                                                    </span>
                                                </div>
                                                
                                                <h4 class="font-medium text-gray-900 mb-2">{{ $question->title }}</h4>
                                                <p class="text-gray-600 mb-3">{{ $question->content }}</p>
                                                
                                                @if($question->type !== 'essay' && $question->options)
                                                    <div class="space-y-1">
                                                        @foreach($question->options as $option)
                                                            <div class="flex items-center text-sm">
                                                                <span class="w-6 h-6 rounded-full border-2 mr-2 flex items-center justify-center
                                                                    {{ $option->is_correct ? 'bg-green-100 border-green-500 text-green-700' : 'border-gray-300' }}">
                                                                    @if($option->is_correct) ✓ @endif
                                                                </span>
                                                                {{ $option->content }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <form method="POST" action="{{ route('teacher.assessment.remove-question', [$assessment, $question]) }}" 
                                                  onsubmit="return confirm('Tem certeza que deseja remover esta questão?')" class="ml-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    🗑️ Remover
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4">❓</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma questão adicionada</h3>
                                <p class="text-gray-500 mb-4">Adicione questões para completar sua avaliação.</p>
                                <button onclick="openQuestionModal()" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Adicionar Primeira Questão
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Estatísticas -->
            @if(isset($stats))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">📊 Estatísticas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                                <div class="text-sm text-gray-600">Total de Tentativas</div>
                            </div>
                            
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                                <div class="text-sm text-gray-600">Concluídas</div>
                            </div>
                            
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</div>
                                <div class="text-sm text-gray-600">Em Andamento</div>
                            </div>
                            
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['average_score'], 1) }}%</div>
                                <div class="text-sm text-gray-600">Média Geral</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para adicionar questões -->
    <div id="questionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Adicionar Questões</h3>
                    <button onclick="closeQuestionModal()" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Fechar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Filtros de Busca -->
                <div class="mb-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                            <input type="text" id="questionSearch" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Título ou conteúdo...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                            <select id="questionType" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todos</option>
                                <option value="multiple_choice">Múltipla Escolha</option>
                                <option value="true_false">Verdadeiro/Falso</option>
                                <option value="essay">Dissertativa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dificuldade</label>
                            <select id="questionDifficulty" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todas</option>
                                <option value="easy">Fácil</option>
                                <option value="medium">Médio</option>
                                <option value="hard">Difícil</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="searchQuestions()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        🔍 Buscar Questões
                    </button>
                </div>
                
                <!-- Lista de Questões -->
                <div id="questionsList" class="space-y-2 max-h-96 overflow-y-auto">
                    <p class="text-gray-500 text-center py-4">Use os filtros acima para buscar questões</p>
                </div>
                
                <!-- Questões Selecionadas -->
                <div id="selectedQuestions" class="mt-4" style="display: none;">
                    <h4 class="font-medium text-gray-900 mb-2">Questões Selecionadas:</h4>
                    <div id="selectedQuestionsList" class="space-y-1 mb-4"></div>
                </div>
                
                <div class="flex justify-between">
                    <button onclick="closeQuestionModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button onclick="addSelectedQuestions()" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            style="display: none;" id="addQuestionsBtn">
                        ➕ Adicionar Questões
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedQuestions = [];
        
        function openQuestionModal() {
            document.getElementById('questionModal').classList.remove('hidden');
            selectedQuestions = [];
            updateSelectedQuestions();
            
            // Buscar questões automaticamente ao abrir o modal
            console.log('Modal aberto, buscando questões automaticamente...');
            searchQuestions();
        }
        
        function closeQuestionModal() {
            document.getElementById('questionModal').classList.add('hidden');
            document.getElementById('questionsList').innerHTML = '<p class="text-gray-500 text-center py-4">Use os filtros acima para buscar questões</p>';
            selectedQuestions = [];
            updateSelectedQuestions();
        }
        
        async function searchQuestions() {
            const search = document.getElementById('questionSearch').value;
            const type = document.getElementById('questionType').value;
            const difficulty = document.getElementById('questionDifficulty').value;
            
            const params = new URLSearchParams({
                assessment_id: {{ $assessment->id }},
                subject_id: {{ $assessment->subject_id }}
            });
            
            if (search) params.append('search', search);
            if (type) params.append('type', type);
            if (difficulty) params.append('difficulty', difficulty);
            
            console.log('Buscando questões com parâmetros:', params.toString());
            
            try {
                const url = `{{ url('/questions/search/api') }}?${params}`;
                console.log('URL da requisição:', url);
                
                const response = await fetch(url);
                console.log('Status da resposta:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const questions = await response.json();
                console.log('Questões recebidas:', questions.length, questions);
                
                displayQuestions(questions);
            } catch (error) {
                console.error('Erro ao buscar questões:', error);
                document.getElementById('questionsList').innerHTML = `<p class="text-red-500 text-center py-4">Erro ao carregar questões: ${error.message}</p>`;
            }
        }
        
        function displayQuestions(questions) {
            const container = document.getElementById('questionsList');
            
            if (questions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhuma questão encontrada</p>';
                return;
            }
            
            container.innerHTML = questions.map(question => `
                <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h4 class="font-medium text-gray-900">${question.title}</h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    ${question.type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 
                                      question.type === 'true_false' ? 'bg-green-100 text-green-800' : 
                                      'bg-purple-100 text-purple-800'}">
                                    ${question.type === 'multiple_choice' ? 'Múltipla Escolha' : 
                                      question.type === 'true_false' ? 'V/F' : 'Dissertativa'}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    ${question.difficulty === 'easy' ? 'bg-green-100 text-green-800' : 
                                      question.difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                                      'bg-red-100 text-red-800'}">
                                    ${question.difficulty === 'easy' ? 'Fácil' : 
                                      question.difficulty === 'medium' ? 'Médio' : 'Difícil'}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">${question.content.substring(0, 100)}...</p>
                            <p class="text-xs text-gray-500">${question.points} pontos</p>
                        </div>
                        <button onclick="toggleQuestionSelection(${question.id}, '${question.title.replace(/'/g, "\\'")}')" 
                                class="ml-2 px-3 py-1 text-sm rounded font-medium question-select-btn-${question.id}
                                ${selectedQuestions.find(q => q.id === question.id) ? 
                                  'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}">
                            ${selectedQuestions.find(q => q.id === question.id) ? '✓ Selecionada' : 'Selecionar'}
                        </button>
                    </div>
                </div>
            `).join('');
        }
        
        function toggleQuestionSelection(questionId, questionTitle) {
            const index = selectedQuestions.findIndex(q => q.id === questionId);
            
            if (index > -1) {
                selectedQuestions.splice(index, 1);
            } else {
                selectedQuestions.push({ id: questionId, title: questionTitle });
            }
            
            updateSelectedQuestions();
            
            // Atualizar botão específico
            const btn = document.querySelector(`.question-select-btn-${questionId}`);
            if (btn) {
                const isSelected = selectedQuestions.find(q => q.id === questionId);
                btn.className = `ml-2 px-3 py-1 text-sm rounded font-medium question-select-btn-${questionId} ${
                    isSelected ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                }`;
                btn.textContent = isSelected ? '✓ Selecionada' : 'Selecionar';
            }
        }
        
        function updateSelectedQuestions() {
            const container = document.getElementById('selectedQuestions');
            const list = document.getElementById('selectedQuestionsList');
            const addBtn = document.getElementById('addQuestionsBtn');
            
            if (selectedQuestions.length > 0) {
                container.style.display = 'block';
                addBtn.style.display = 'inline-block';
                
                list.innerHTML = selectedQuestions.map(q => `
                    <div class="flex items-center justify-between bg-blue-50 px-3 py-1 rounded">
                        <span class="text-sm">${q.title}</span>
                        <button onclick="toggleQuestionSelection(${q.id}, '${q.title}')" 
                                class="text-red-500 hover:text-red-700 text-sm">
                            ✕
                        </button>
                    </div>
                `).join('');
            } else {
                container.style.display = 'none';
                addBtn.style.display = 'none';
            }
        }
        
        async function addSelectedQuestions() {
            if (selectedQuestions.length === 0) return;
            
            const questionIds = selectedQuestions.map(q => q.id);
            const addBtn = document.getElementById('addQuestionsBtn');
            
            // Desabilitar botão durante o processo
            addBtn.disabled = true;
            addBtn.textContent = '⏳ Adicionando...';
            
            try {
                const response = await fetch(`{{ url('/teacher/assessments/' . $assessment->id . '/questions') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        question_ids: questionIds
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Mostrar mensagem de sucesso
                    alert(data.message);
                    // Fechar modal e recarregar página
                    closeQuestionModal();
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao adicionar questões');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro de conexão ao adicionar questões');
            } finally {
                // Reabilitar botão
                addBtn.disabled = false;
                addBtn.textContent = '➕ Adicionar Questões';
            }
        }
        
        function confirmDelete() {
            const studentAttempts = {{ $assessment->studentAssessments()->count() }};
            const assessmentTitle = "{{ $assessment->title }}";
            
            let message = `Tem certeza que deseja excluir a avaliação "${assessmentTitle}"?`;
            
            if (studentAttempts > 0) {
                alert(`Não é possível excluir esta avaliação pois ela possui ${studentAttempts} tentativa(s) de aluno(s).`);
                return;
            }
            
            message += '\n\n⚠️ Esta ação não pode ser desfeita!';
            message += '\n• Todas as questões serão removidas da avaliação';
            message += '\n• As turmas serão desvinculadas';
            message += '\n• A avaliação será permanentemente excluída';
            
            if (confirm(message)) {
                // Confirmação dupla para segurança
                if (confirm('Confirma DEFINITIVAMENTE a exclusão da avaliação?')) {
                    document.getElementById('deleteForm').submit();
                }
            }
        }
    </script>
</x-app-layout>