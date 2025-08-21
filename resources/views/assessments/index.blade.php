<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            📝
                        </span>
                        @if(auth()->user()->isTeacher())
                            Minhas Avaliações
                        @elseif(auth()->user()->isAdmin())
                            Gerenciar Avaliações
                        @else
                            Avaliações Disponíveis
                        @endif
                    </h2>
                    <p class="text-purple-100 mt-1">
                        @if(auth()->user()->isTeacher())
                            Crie, edite e gerencie suas avaliações
                        @elseif(auth()->user()->isAdmin())
                            Administre todas as avaliações do sistema
                        @else
                            Visualize e realize suas avaliações
                        @endif
                    </p>
                </div>
                
                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    <div class="flex space-x-3">
                        <a href="{{ route('assessments.create') }}" 
                           class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                            <span>➕</span>
                            <span>Nova Avaliação</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <!-- Dashboard de Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold">{{ $assessments->total() }}</div>
                                    <div class="text-blue-100 text-sm">Total de Avaliações</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">📝</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold">{{ $assessments->where('status', 'published')->count() }}</div>
                                    <div class="text-green-100 text-sm">Publicadas</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">✅</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold">{{ $assessments->where('status', 'draft')->count() }}</div>
                                    <div class="text-yellow-100 text-sm">Rascunhos</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">📝</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-3xl font-bold">{{ $assessments->where('status', 'closed')->count() }}</div>
                                    <div class="text-red-100 text-sm">Fechadas</div>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                    <span class="text-2xl">🔒</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filtros Modernos -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">🔍</span>
                        Filtros e Busca
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="search" :value="__('🔍 Buscar Avaliação')" />
                            <x-text-input id="search" name="search" type="text" 
                                        class="mt-1 block w-full" 
                                        :value="request('search')" 
                                        placeholder="Digite o título da avaliação..." />
                        </div>
                        
                        <div>
                            <x-input-label for="status" :value="__('📊 Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">🔄 Todos os Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Rascunho</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>✅ Publicada</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>🔒 Fechada</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('assessments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                🔄
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Avaliações -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-purple-100 text-purple-600 rounded-lg p-2 mr-3">📋</span>
                        Lista de Avaliações
                        <span class="ml-2 bg-gray-100 text-gray-600 text-sm px-2 py-1 rounded-full">
                            {{ $assessments->total() }} total
                        </span>
                    </h3>
                </div>
                <div class="p-6">
                    @if($assessments->count() > 0)
                        <div class="space-y-6">
                            @foreach($assessments as $assessment)
                                <div class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <h3 class="text-xl font-bold text-gray-900">
                                                    {{ $assessment->title }}
                                                </h3>
                                                
                                                <!-- Status Badge Melhorado -->
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full shadow-sm
                                                    @if($assessment->status === 'published') bg-gradient-to-r from-green-400 to-green-500 text-white
                                                    @elseif($assessment->status === 'draft') bg-gradient-to-r from-yellow-400 to-orange-400 text-white
                                                    @else bg-gradient-to-r from-red-400 to-red-500 text-white @endif">
                                                    @if($assessment->status === 'published') ✅ Publicada
                                                    @elseif($assessment->status === 'draft') 📝 Rascunho
                                                    @else 🔒 Fechada @endif
                                                </span>

                                                @if(auth()->user()->isAdmin())
                                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                        👨‍🏫 {{ $assessment->teacher->name ?? 'Professor não definido' }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ Str::limit($assessment->description, 150) }}</p>
                                            
                                            <!-- Grid de Informações Melhorado -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                                <div class="bg-blue-50 rounded-lg p-3 text-center">
                                                    <div class="text-blue-600 text-lg mb-1">📚</div>
                                                    <div class="text-xs text-gray-500 mb-1">Disciplina</div>
                                                    <div class="font-semibold text-gray-900 text-sm">{{ $assessment->subject->name }}</div>
                                                </div>
                                                <div class="bg-purple-50 rounded-lg p-3 text-center">
                                                    <div class="text-purple-600 text-lg mb-1">⏱️</div>
                                                    <div class="text-xs text-gray-500 mb-1">Duração</div>
                                                    <div class="font-semibold text-gray-900 text-sm">{{ $assessment->duration_minutes }}min</div>
                                                </div>
                                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                                    <div class="text-green-600 text-lg mb-1">📊</div>
                                                    <div class="text-xs text-gray-500 mb-1">Pontuação</div>
                                                    <div class="font-semibold text-gray-900 text-sm">{{ $assessment->max_score }}pts</div>
                                                </div>
                                                <div class="bg-orange-50 rounded-lg p-3 text-center">
                                                    <div class="text-orange-600 text-lg mb-1">👥</div>
                                                    <div class="text-xs text-gray-500 mb-1">Tentativas</div>
                                                    <div class="font-semibold text-gray-900 text-sm">{{ $assessment->studentAssessments->count() }}</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Informações de Data -->
                                            <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                                <div class="flex items-center text-gray-600">
                                                    <span class="font-medium mr-2">📅 Período:</span>
                                                    <span class="text-blue-600 font-medium">{{ $assessment->opens_at->format('d/m/Y H:i') }}</span>
                                                    <span class="mx-2">até</span>
                                                    <span class="text-red-600 font-medium">{{ $assessment->closes_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                @if($assessment->questions->count() > 0)
                                                    <div class="mt-1 text-gray-500">
                                                        <span class="font-medium">❓ Questões:</span> {{ $assessment->questions->count() }}
                                                        @if($assessment->classes->count() > 0)
                                                            • <span class="font-medium">🏫 Turmas:</span> {{ $assessment->classes->count() }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Ações Modernas -->
                                        <div class="ml-6 flex flex-col gap-3 min-w-[200px]">
                                            @if(auth()->user()->isStudent())
                                                @if($assessment->canBeAttemptedBy(auth()->user()))
                                                    <a href="{{ route('student.assessments.start', $assessment) }}" 
                                                       class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl text-center transition-all duration-200 transform hover:scale-105 shadow-lg">
                                                        🚀 Iniciar Avaliação
                                                    </a>
                                                @else
                                                    <span class="bg-gray-300 text-gray-600 font-bold py-3 px-4 rounded-xl text-center cursor-not-allowed">
                                                        ⏰ Indisponível
                                                    </span>
                                                @endif
                                            @else
                                                <!-- Botão Ver Sempre Visível -->
                                                <a href="{{ route('assessments.show', $assessment) }}" 
                                                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg text-center transition-all duration-200 flex items-center justify-center">
                                                    👁️ Visualizar
                                                </a>
                                                
                                                @if((auth()->user()->isTeacher() && $assessment->teacher_id === auth()->id()) || auth()->user()->isAdmin())
                                                    <!-- Ações Principais -->
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <a href="{{ route('assessments.edit', $assessment) }}" 
                                                           class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-2 px-3 rounded-lg text-center transition-all duration-200 text-sm">
                                                            ✏️ Editar
                                                        </a>
                                                        
                                                        @if(Route::has('assessments.duplicate'))
                                                            <form method="POST" action="{{ route('assessments.duplicate', $assessment) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold py-2 px-3 rounded-lg transition-all duration-200 text-sm"
                                                                        onclick="return confirm('Deseja duplicar esta avaliação?')">
                                                                    📋 Duplicar
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Ações de Status -->
                                                    @if($assessment->status === 'draft')
                                                        <form method="POST" action="{{ route('teacher.assessment.publish', $assessment) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200">
                                                                📢 Publicar Avaliação
                                                            </button>
                                                        </form>
                                                    @elseif($assessment->status === 'published')
                                                        <form method="POST" action="{{ route('teacher.assessment.close', $assessment) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200"
                                                                    onclick="return confirm('Tem certeza que deseja fechar esta avaliação?')">
                                                                🔒 Fechar Avaliação
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <!-- Botão de Excluir -->
                                                    @if($assessment->studentAssessments()->count() === 0)
                                                        <button onclick="confirmDeleteAssessment({{ $assessment->id }}, '{{ addslashes($assessment->title) }}')" 
                                                                class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200">
                                                            🗑️ Excluir
                                                        </button>
                                                    @else
                                                        <div class="relative group">
                                                            <span class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg text-center cursor-not-allowed block">
                                                                🔒 Protegida
                                                            </span>
                                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                                                                {{ $assessment->studentAssessments()->count() }} tentativa(s) de aluno(s)
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Formulário de Exclusão -->
                                                    <form id="deleteForm-{{ $assessment->id }}" method="POST" action="{{ route('assessments.destroy', $assessment) }}" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Paginação Moderna -->
                        <x-pagination-info :paginator="$assessments" item-name="avaliações" />
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📝</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma avaliação encontrada</h3>
                            <p class="text-gray-500 mb-4">
                                @if(auth()->user()->isTeacher())
                                    Comece criando sua primeira avaliação.
                                @else
                                    Não há avaliações disponíveis no momento.
                                @endif
                            </p>
                            
                            @if(auth()->user()->isTeacher())
                                <a href="{{ route('assessments.create') }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Criar Primeira Avaliação
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paginação -->
            <x-pagination-wrapper :paginator="$assessments" item-name="avaliações" />
        </div>
    </div>
    
    <script>
        function confirmDeleteAssessment(assessmentId, assessmentTitle) {
            let message = `Tem certeza que deseja excluir a avaliação "${assessmentTitle}"?`;
            
            message += '\n\n⚠️ Esta ação não pode ser desfeita!';
            message += '\n• Todas as questões serão removidas da avaliação';
            message += '\n• As turmas serão desvinculadas';
            message += '\n• A avaliação será permanentemente excluída';
            
            if (confirm(message)) {
                // Confirmação dupla para segurança
                if (confirm('Confirma DEFINITIVAMENTE a exclusão da avaliação?')) {
                    document.getElementById(`deleteForm-${assessmentId}`).submit();
                }
            }
        }
    </script>
</x-app-layout>