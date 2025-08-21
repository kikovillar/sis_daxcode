<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-orange-600 to-red-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            🔧
                        </span>
                        Gerenciar Questões das Avaliações
                    </h2>
                    <p class="text-orange-100 mt-1">Adicione ou remova questões das suas avaliações</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('assessment-questions.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="search" :value="__('Buscar Avaliação')" />
                            <x-text-input id="search" name="search" type="text" 
                                        class="mt-1 block w-full" 
                                        :value="request('search')" 
                                        placeholder="Digite o título da avaliação..." />
                        </div>

                        <div>
                            <x-input-label for="subject_id" :value="__('Disciplina')" />
                            <select id="subject_id" name="subject_id" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Todas as disciplinas</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" 
                                    class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition-colors mr-2">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('assessment-questions.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors">
                                🗑️ Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Avaliações -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($assessments->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($assessments as $assessment)
                                <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                                    <!-- Header da Avaliação -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $assessment->title }}</h3>
                                            <p class="text-sm text-gray-600">📚 {{ $assessment->subject->name }}</p>
                                        </div>
                                        <div class="ml-3">
                                            @if($assessment->status === 'published')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Publicada
                                                </span>
                                            @elseif($assessment->status === 'draft')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    📝 Rascunho
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    ⏸️ Inativa
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Estatísticas -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-blue-50 rounded-lg p-3 text-center">
                                            <div class="text-2xl font-bold text-blue-600">{{ $assessment->questions->count() }}</div>
                                            <div class="text-xs text-blue-600 font-medium">Questões</div>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-3 text-center">
                                            <div class="text-2xl font-bold text-purple-600">{{ $assessment->max_score ?? 0 }}</div>
                                            <div class="text-xs text-purple-600 font-medium">Pontos</div>
                                        </div>
                                    </div>

                                    <!-- Informações Adicionais -->
                                    <div class="text-sm text-gray-600 mb-4 space-y-1">
                                        @if($assessment->duration_minutes)
                                            <div class="flex items-center">
                                                <span class="mr-2">⏱️</span>
                                                <span>{{ $assessment->duration_minutes }} minutos</span>
                                            </div>
                                        @endif
                                        @if($assessment->start_date)
                                            <div class="flex items-center">
                                                <span class="mr-2">📅</span>
                                                <span>{{ \Carbon\Carbon::parse($assessment->start_date)->format('d/m/Y H:i') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Ações -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('assessment-questions.manage', $assessment) }}" 
                                           class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center text-sm">
                                            🔧 Gerenciar Questões
                                        </a>
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded-lg transition-colors text-sm">
                                            👁️
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        <div class="mt-8">
                            {{ $assessments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📝</div>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">Nenhuma avaliação encontrada</h3>
                            <p class="text-gray-500 mb-6">
                                @if(request()->hasAny(['search', 'subject_id']))
                                    Tente ajustar os filtros ou criar uma nova avaliação.
                                @else
                                    Você ainda não criou nenhuma avaliação.
                                @endif
                            </p>
                            <a href="{{ route('assessments.create') }}" 
                               class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                ➕ Criar Nova Avaliação
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>