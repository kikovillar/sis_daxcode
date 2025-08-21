<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ❓
                        </span>
                        Gerenciar Questões
                    </h2>
                    <p class="text-blue-100 mt-1">{{ $assessment->title }}</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('assessments.edit', $assessment) }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar para Edição</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Mensagens -->
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

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Questões da Avaliação -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                📝 Questões na Avaliação ({{ $assessment->questions->count() }})
                            </h3>
                            
                            @if($assessment->questions->count() > 0)
                                <div class="space-y-4" id="assessment-questions">
                                    @foreach($assessment->questions->sortBy('pivot.order') as $question)
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                            #{{ $question->pivot->order }}
                                                        </span>
                                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                            {{ ucfirst($question->type) }}
                                                        </span>
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                            {{ $question->pivot->points_override ?? $question->points }} pts
                                                        </span>
                                                    </div>
                                                    <h4 class="font-medium text-gray-900 mb-1">{{ $question->title }}</h4>
                                                    <p class="text-gray-600 text-sm">{{ Str::limit($question->content, 100) }}</p>
                                                </div>
                                                
                                                <div class="flex space-x-2 ml-4">
                                                    <form method="POST" action="{{ route('assessment-questions.remove', [$assessment, $question]) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-800 p-1"
                                                                onclick="return confirm('Remover esta questão da avaliação?')"
                                                                title="Remover questão">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p>Nenhuma questão adicionada ainda</p>
                                </div>
                            @endif
                        </div>

                        <!-- Questões Disponíveis -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                ➕ Questões Disponíveis ({{ $availableQuestions->count() }})
                            </h3>
                            
                            @if($availableQuestions->count() > 0)
                                <form method="POST" action="{{ route('assessment-questions.add', $assessment) }}" id="addQuestionsForm">
                                    @csrf
                                    <div class="space-y-4 max-h-96 overflow-y-auto" id="available-questions">
                                        @foreach($availableQuestions as $question)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                                <div class="flex items-start space-x-3">
                                                    <input type="checkbox" 
                                                           name="question_ids[]" 
                                                           value="{{ $question->id }}"
                                                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                {{ ucfirst($question->type) }}
                                                            </span>
                                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                {{ $question->points }} pts
                                                            </span>
                                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                                {{ ucfirst($question->difficulty) }}
                                                            </span>
                                                        </div>
                                                        <h4 class="font-medium text-gray-900 mb-1">{{ $question->title }}</h4>
                                                        <p class="text-gray-600 text-sm">{{ Str::limit($question->content, 100) }}</p>
                                                        
                                                        @if($question->options->count() > 0)
                                                            <div class="mt-2 text-xs text-gray-500">
                                                                {{ $question->options->count() }} opções
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-6 flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            <span id="selected-count">0</span> questões selecionadas
                                        </div>
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                                id="add-questions-btn"
                                                disabled>
                                            ➕ Adicionar Questões
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">🔍</div>
                                    <p>Nenhuma questão disponível para esta disciplina</p>
                                    <a href="{{ route('questions.create') }}" 
                                       class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Criar Nova Questão
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
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="question_ids[]"]');
            const selectedCount = document.getElementById('selected-count');
            const addButton = document.getElementById('add-questions-btn');
            
            function updateSelection() {
                const checked = document.querySelectorAll('input[name="question_ids[]"]:checked');
                selectedCount.textContent = checked.length;
                addButton.disabled = checked.length === 0;
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelection);
            });
            
            // Confirmar antes de adicionar
            document.getElementById('addQuestionsForm').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('input[name="question_ids[]"]:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Selecione pelo menos uma questão para adicionar.');
                    return;
                }
                
                if (!confirm(`Adicionar ${checked.length} questão(ões) à avaliação?`)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>