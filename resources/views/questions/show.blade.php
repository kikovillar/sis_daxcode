<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📝 {{ $question->title }}
            </h2>
            
            <div class="flex space-x-2">
                @if(auth()->user()->isAdmin() || (auth()->user()->isTeacher() && $question->created_by === auth()->id()))
                    <a href="{{ route('questions.edit', $question) }}" 
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        ✏️ Editar
                    </a>
                @endif
                
                <a href="{{ route('questions.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações da Questão -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Badges e Metadados -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($question->type === 'multiple_choice') bg-blue-100 text-blue-800
                            @elseif($question->type === 'true_false') bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800 @endif">
                            @if($question->type === 'multiple_choice') 📝 Múltipla Escolha
                            @elseif($question->type === 'true_false') ✅ Verdadeiro/Falso
                            @else 📄 Dissertativa @endif
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($question->difficulty === 'easy') bg-green-100 text-green-800
                            @elseif($question->difficulty === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($question->difficulty === 'easy') 😊 Fácil
                            @elseif($question->difficulty === 'medium') 😐 Médio
                            @else 😰 Difícil @endif
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            🏆 {{ $question->points }} pontos
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            📚 {{ $question->subject->name }}
                        </span>
                    </div>

                    <!-- Metadados -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Criado por:</span>
                            <p class="text-sm text-gray-900">
                                {{ $question->creator ? $question->creator->name : 'Sistema' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Data de criação:</span>
                            <p class="text-sm text-gray-900">{{ $question->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Última atualização:</span>
                            <p class="text-sm text-gray-900">{{ $question->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Conteúdo da Questão -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">📋 Enunciado</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $question->content }}</p>
                        </div>
                        
                        <!-- Imagem da Questão -->
                        @if($question->image_path)
                            <div class="mt-4">
                                <h4 class="text-md font-medium text-gray-900 mb-2">🖼️ Imagem</h4>
                                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                    <img src="{{ asset('storage/' . $question->image_path) }}" 
                                         alt="{{ $question->image_description ?? 'Imagem da questão' }}"
                                         class="max-w-full h-auto max-h-96 rounded-lg shadow-sm">
                                    @if($question->image_description)
                                        <p class="text-sm text-gray-600 mt-2">
                                            <strong>Descrição:</strong> {{ $question->image_description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Opções (para questões objetivas) -->
                    @if($question->isObjective() && $question->options->count() > 0)
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">
                                @if($question->type === 'multiple_choice') 📝 Opções de Resposta
                                @else ✅ Opções @endif
                            </h3>
                            
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <div class="flex items-center p-3 rounded-lg border
                                        {{ $option->is_correct ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                        <div class="flex-shrink-0 mr-3">
                                            @if($option->is_correct)
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-green-500 text-white rounded-full text-sm font-bold">
                                                    ✓
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-300 text-gray-600 rounded-full text-sm">
                                                    {{ chr(65 + $loop->index) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-gray-900">{{ $option->content }}</p>
                                            @if($option->is_correct)
                                                <p class="text-sm text-green-600 font-medium">✅ Resposta correta</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Explicação -->
                    @if($question->explanation)
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">💡 Explicação</h3>
                            <div class="prose max-w-none">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $question->explanation }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estatísticas de Uso -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Estatísticas de Uso</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $question->assessments->count() }}</div>
                            <div class="text-sm text-gray-600">Avaliações</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $question->studentAnswers->count() }}</div>
                            <div class="text-sm text-gray-600">Respostas</div>
                        </div>
                        
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $question->studentAnswers->where('is_correct', true)->count() }}
                            </div>
                            <div class="text-sm text-gray-600">Acertos</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">
                                @php
                                    $total = $question->studentAnswers->count();
                                    $correct = $question->studentAnswers->where('is_correct', true)->count();
                                    $accuracy = $total > 0 ? round(($correct / $total) * 100, 1) : 0;
                                @endphp
                                {{ $accuracy }}%
                            </div>
                            <div class="text-sm text-gray-600">Taxa de Acerto</div>
                        </div>
                    </div>

                    @if($question->assessments->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">🎯 Usada nas Avaliações:</h4>
                            <div class="space-y-2">
                                @foreach($question->assessments as $assessment)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium">{{ $assessment->title }}</span>
                                            <span class="text-sm text-gray-500 ml-2">({{ $assessment->subject->name }})</span>
                                        </div>
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            Ver avaliação →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>