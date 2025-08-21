<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            ❓
                        </span>
                        Banco de Questões
                    </h2>
                    <p class="text-purple-100 mt-1">Gerencie suas questões e organize por disciplinas</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('questions.create') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>➕</span>
                        <span>Nova Questão</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Lista de Questões -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if(isset($questions) && $questions->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($questions as $question)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $question->title }}</h3>
                                            
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($question->type === 'multiple_choice') bg-blue-100 text-blue-800
                                                @elseif($question->type === 'true_false') bg-green-100 text-green-800
                                                @else bg-purple-100 text-purple-800 @endif">
                                                @if($question->type === 'multiple_choice') 🔘 Múltipla Escolha
                                                @elseif($question->type === 'true_false') ✅ Verdadeiro/Falso
                                                @else 📝 Dissertativa @endif
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-3">{{ Str::limit($question->content, 150) }}</p>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">📚 Disciplina:</span><br>
                                                {{ $question->subject->name }}
                                            </div>
                                            <div>
                                                <span class="font-medium">🏆 Pontos:</span><br>
                                                {{ $question->points }}
                                            </div>
                                            <div>
                                                <span class="font-medium">📅 Criada:</span><br>
                                                {{ $question->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-6 flex flex-col space-y-2">
                                        <a href="{{ route('questions.show', $question) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                                            👁️ Ver
                                        </a>
                                        
                                        <a href="{{ route('questions.edit', $question) }}" 
                                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                                            ✏️ Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">❓</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma questão encontrada</h3>
                        <p class="text-gray-500 mb-6">Você ainda não criou nenhuma questão.</p>
                        <a href="{{ route('questions.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                            ➕ Criar Primeira Questão
                        </a>
                    </div>
                @endif
            </div>

            <!-- Paginação -->
            @if(isset($questions))
                <x-pagination-wrapper :paginator="$questions" item-name="questões" />
            @endif
        </div>
    </div>
</x-app-layout>