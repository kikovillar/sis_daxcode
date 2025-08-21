<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📚 {{ $subject->name }}
            </h2>
            
            <div class="flex space-x-2">
                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    <a href="{{ route('subjects.edit', $subject) }}" 
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        ✏️ Editar
                    </a>
                @endif
                
                <a href="{{ route('questions.create', ['subject_id' => $subject->id]) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    ➕ Nova Questão
                </a>
                
                <a href="{{ route('assessments.create', ['subject_id' => $subject->id]) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    📝 Nova Avaliação
                </a>
                
                <a href="{{ route('subjects.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Informações da Disciplina -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Descrição</h3>
                            <p class="text-gray-600">
                                {{ $subject->description ?: 'Nenhuma descrição fornecida.' }}
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Criada em</h3>
                            <p class="text-gray-600">
                                {{ $subject->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📊 Estatísticas</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_questions'] }}</div>
                            <div class="text-sm text-gray-600">Total de Questões</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ $stats['total_assessments'] }}</div>
                            <div class="text-sm text-gray-600">Total de Avaliações</div>
                        </div>
                        
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-3xl font-bold text-yellow-600">{{ $stats['questions_by_difficulty']['easy'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Questões Fáceis</div>
                        </div>
                        
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-3xl font-bold text-red-600">{{ $stats['questions_by_difficulty']['hard'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Questões Difíceis</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questões Recentes -->
            @if($subject->questions->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">❓ Questões Recentes</h3>
                            <a href="{{ route('questions.index', ['subject_id' => $subject->id]) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                Ver todas →
                            </a>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($subject->questions as $question)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                </span>
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                                                    {{ ucfirst($question->difficulty) }}
                                                </span>
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                                    {{ $question->points }} pts
                                                </span>
                                            </div>
                                            
                                            <h4 class="font-medium text-gray-900 mb-1">{{ $question->title }}</h4>
                                            <p class="text-gray-600 text-sm">{{ Str::limit($question->content, 100) }}</p>
                                            
                                            @if($question->creator)
                                                <p class="text-xs text-gray-500 mt-2">
                                                    Criada por {{ $question->creator->name }} em {{ $question->created_at->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <a href="{{ route('questions.show', $question) }}" 
                                           class="text-blue-600 hover:text-blue-800 ml-4">
                                            👁️ Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Avaliações Recentes -->
            @if($subject->assessments->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">📝 Avaliações Recentes</h3>
                            <a href="{{ route('assessments.index', ['subject_id' => $subject->id]) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                Ver todas →
                            </a>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($subject->assessments as $assessment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="bg-{{ $assessment->status === 'published' ? 'green' : ($assessment->status === 'draft' ? 'yellow' : 'gray') }}-100 
                                                           text-{{ $assessment->status === 'published' ? 'green' : ($assessment->status === 'draft' ? 'yellow' : 'gray') }}-800 
                                                           text-xs font-medium px-2 py-1 rounded">
                                                    {{ ucfirst($assessment->status) }}
                                                </span>
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                                    {{ $assessment->questions()->count() }} questões
                                                </span>
                                            </div>
                                            
                                            <h4 class="font-medium text-gray-900 mb-1">{{ $assessment->title }}</h4>
                                            <p class="text-gray-600 text-sm">{{ Str::limit($assessment->description, 100) }}</p>
                                            
                                            @if($assessment->teacher)
                                                <p class="text-xs text-gray-500 mt-2">
                                                    Criada por {{ $assessment->teacher->name }} em {{ $assessment->created_at->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="text-blue-600 hover:text-blue-800 ml-4">
                                            👁️ Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ações Rápidas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">🚀 Ações Rápidas</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('questions.create', ['subject_id' => $subject->id]) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                            <div class="text-2xl mb-2">❓</div>
                            <div class="font-medium">Criar Questão</div>
                            <div class="text-sm text-gray-600">Adicionar nova questão para esta disciplina</div>
                        </a>
                        
                        <a href="{{ route('assessments.create', ['subject_id' => $subject->id]) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                            <div class="text-2xl mb-2">📝</div>
                            <div class="font-medium">Criar Avaliação</div>
                            <div class="text-sm text-gray-600">Criar nova avaliação para esta disciplina</div>
                        </a>
                        
                        <a href="{{ route('questions.index', ['subject_id' => $subject->id]) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                            <div class="text-2xl mb-2">📋</div>
                            <div class="font-medium">Ver Questões</div>
                            <div class="text-sm text-gray-600">Explorar todas as questões desta disciplina</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>