<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            📚
                        </span>
                        Disciplinas
                    </h2>
                    <p class="text-indigo-100 mt-1">Gerencie as disciplinas do sistema</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('subjects.create') }}" 
                       class="bg-emerald-500 bg-opacity-90 backdrop-blur-sm hover:bg-opacity-100 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>➕</span>
                        <span>Nova Disciplina</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Lista de Disciplinas -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if(isset($subjects) && $subjects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($subjects as $subject)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $subject->name }}</h3>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        📚 Disciplina
                                    </span>
                                </div>
                                
                                @if($subject->description)
                                    <p class="text-gray-600 mb-4">{{ $subject->description }}</p>
                                @endif
                                
                                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                                    <div>
                                        <span class="font-medium">❓ Questões:</span><br>
                                        {{ $subject->questions->count() ?? 0 }}
                                    </div>
                                    <div>
                                        <span class="font-medium">📝 Avaliações:</span><br>
                                        {{ $subject->assessments->count() ?? 0 }}
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('subjects.show', $subject) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-center transition-colors">
                                        👁️ Ver
                                    </a>
                                    
                                    <a href="{{ route('subjects.edit', $subject) }}" 
                                       class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-lg text-center transition-colors">
                                        ✏️ Editar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📚</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma disciplina encontrada</h3>
                        <p class="text-gray-500 mb-6">Você ainda não criou nenhuma disciplina.</p>
                        <a href="{{ route('subjects.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                            ➕ Criar Primeira Disciplina
                        </a>
                    </div>
                @endif
            </div>

            <!-- Paginação -->
            @if(isset($subjects))
                <x-pagination-wrapper :paginator="$subjects" item-name="disciplinas" />
            @endif
        </div>
    </div>
</x-app-layout>