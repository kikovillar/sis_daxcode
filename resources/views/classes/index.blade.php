<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            🏫
                        </span>
                        Gerenciar Turmas
                    </h2>
                    <p class="text-blue-100 mt-1">Organize e gerencie suas turmas de forma eficiente</p>
                </div>
                
                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    <a href="{{ route('classes.create') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span class="text-lg">➕</span>
                        <span>Nova Turma</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros Modernos -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">🔍</span>
                        Filtros de Busca
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('classes.index') }}" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                    <span class="mr-2">🔤</span>
                                    Buscar Turma
                                </label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Digite o nome ou descrição da turma...">
                            </div>
                            
                            @if(auth()->user()->isAdmin())
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                        <span class="mr-2">👨‍🏫</span>
                                        Professor
                                    </label>
                                    <select name="teacher_id" 
                                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <option value="">Todos os professores</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                    {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            
                            <div class="flex items-end space-x-3">
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg">
                                    <span>🔍</span>
                                    <span>Filtrar</span>
                                </button>
                                <a href="{{ route('classes.index') }}" 
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2">
                                    <span>🔄</span>
                                    <span>Limpar</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Turmas Moderna -->
            <div class="space-y-6">
                @if($classes->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($classes as $class)
                            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                <!-- Header do Card -->
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold mb-2 line-clamp-2">{{ $class->name }}</h3>
                                            <div class="flex items-center text-blue-100">
                                                <span class="mr-2">👨‍🏫</span>
                                                <span class="text-sm">{{ $class->teacher->name }}</span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id)
                                                <div class="relative group">
                                                    <button class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-2 transition-all duration-200">
                                                        ⚙️
                                                    </button>
                                                    <div class="absolute right-0 top-full mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2 min-w-[150px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                                        <a href="{{ route('classes.show', $class) }}" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                                            <span class="mr-2">👁️</span>
                                                            Ver detalhes
                                                        </a>
                                                        <a href="{{ route('classes.manage-students', $class) }}" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                                            <span class="mr-2">👥</span>
                                                            Gerenciar Alunos
                                                        </a>
                                                        <a href="{{ route('classes.edit', $class) }}" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 transition-colors">
                                                            <span class="mr-2">✏️</span>
                                                            Editar
                                                        </a>
                                                        @if(auth()->user()->isAdmin())
                                                            <form method="POST" action="{{ route('classes.destroy', $class) }}" 
                                                                  class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir esta turma?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                                    <span class="mr-2">🗑️</span>
                                                                    Excluir
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Conteúdo do Card -->
                                <div class="p-6">
                                    @if($class->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $class->description }}</p>
                                    @endif
                                    
                                    <!-- Estatísticas -->
                                    <div class="grid grid-cols-3 gap-4 mb-6">
                                        <div class="text-center">
                                            <div class="bg-blue-100 rounded-xl p-3 mb-2">
                                                <span class="text-2xl">👥</span>
                                            </div>
                                            <div class="text-2xl font-bold text-blue-600">{{ $class->students->count() }}</div>
                                            <div class="text-xs text-gray-500">Alunos</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="bg-green-100 rounded-xl p-3 mb-2">
                                                <span class="text-2xl">📝</span>
                                            </div>
                                            <div class="text-2xl font-bold text-green-600">{{ $class->assessments->count() }}</div>
                                            <div class="text-xs text-gray-500">Avaliações</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="bg-purple-100 rounded-xl p-3 mb-2">
                                                <span class="text-2xl">📅</span>
                                            </div>
                                            <div class="text-sm font-bold text-purple-600">{{ $class->created_at->format('d/m') }}</div>
                                            <div class="text-xs text-gray-500">Criada</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Ações -->
                                    <div class="space-y-2">
                                        <a href="{{ route('classes.show', $class) }}" 
                                           class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg">
                                            <span>👁️</span>
                                            <span>Ver Detalhes</span>
                                        </a>
                                        @if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id)
                                            <a href="{{ route('classes.manage-students', $class) }}" 
                                               class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                                                <span>👥</span>
                                                <span>Gerenciar Alunos</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                        
                    <!-- Paginação Moderna -->
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                            <x-pagination-info :paginator="$classes" item-name="turmas" />
                        </div>
                    </div>
                @else
                    <!-- Estado Vazio Moderno -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="text-center py-16 px-8">
                            <div class="mb-8">
                                <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                                    <span class="text-4xl">🏫</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                    @if(request()->hasAny(['search', 'teacher_id']))
                                        Nenhuma turma encontrada
                                    @else
                                        Suas turmas aparecerão aqui
                                    @endif
                                </h3>
                                <p class="text-gray-600 text-lg max-w-md mx-auto mb-8">
                                    @if(request()->hasAny(['search', 'teacher_id']))
                                        Não encontramos turmas com os filtros aplicados. Tente ajustar sua busca ou limpar os filtros.
                                    @else
                                        Organize seus alunos em turmas para facilitar o gerenciamento de avaliações e atividades.
                                    @endif
                                </p>
                            </div>
                            
                            <div class="space-y-4">
                                @if(request()->hasAny(['search', 'teacher_id']))
                                    <a href="{{ route('classes.index') }}" 
                                       class="inline-flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                                        <span>🔄</span>
                                        <span>Limpar Filtros</span>
                                    </a>
                                @endif
                                
                                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                                    <div>
                                        <a href="{{ route('classes.create') }}" 
                                           class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-200 shadow-lg transform hover:scale-105">
                                            <span class="text-lg">➕</span>
                                            <span>Criar Primeira Turma</span>
                                        </a>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 max-w-4xl mx-auto">
                                        <div class="text-center p-6 bg-blue-50 rounded-xl">
                                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                                <span class="text-xl">👥</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Organize Alunos</h4>
                                            <p class="text-sm text-gray-600">Agrupe seus alunos em turmas para melhor organização</p>
                                        </div>
                                        <div class="text-center p-6 bg-green-50 rounded-xl">
                                            <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                                <span class="text-xl">📝</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Crie Avaliações</h4>
                                            <p class="text-sm text-gray-600">Aplique provas e exercícios específicos para cada turma</p>
                                        </div>
                                        <div class="text-center p-6 bg-purple-50 rounded-xl">
                                            <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                                <span class="text-xl">📊</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Acompanhe Progresso</h4>
                                            <p class="text-sm text-gray-600">Monitore o desempenho e evolução dos seus alunos</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>