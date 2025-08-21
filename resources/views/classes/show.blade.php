<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="bg-white bg-opacity-20 rounded-xl p-3 mr-4">
                            <span class="text-2xl">🏫</span>
                        </div>
                        <div>
                            <h2 class="font-bold text-2xl text-white leading-tight">
                                {{ $class->name }}
                            </h2>
                            <p class="text-blue-100 flex items-center mt-1">
                                <span class="mr-2">👨‍🏫</span>
                                Professor: {{ $class->teacher->name }}
                            </p>
                        </div>
                    </div>
                    @if($class->description)
                        <p class="text-blue-100 text-sm mt-2 max-w-2xl">{{ $class->description }}</p>
                    @endif
                </div>
                
                <div class="flex space-x-3">
                    @if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id)
                        <a href="{{ route('classes.manage-students', $class) }}" 
                           class="bg-green-500 bg-opacity-90 backdrop-blur-sm hover:bg-opacity-100 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                            <span>👥</span>
                            <span>Gerenciar Alunos</span>
                        </a>
                        <a href="{{ route('classes.edit', $class) }}" 
                           class="bg-yellow-500 bg-opacity-90 backdrop-blur-sm hover:bg-opacity-100 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                            <span>✏️</span>
                            <span>Editar</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('classes.index') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Informações da Turma -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Turma</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                    <dd class="text-sm text-gray-900">{{ $class->name }}</dd>
                                </div>
                                @if($class->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                                        <dd class="text-sm text-gray-900">{{ $class->description }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Professor Responsável</dt>
                                    <dd class="text-sm text-gray-900">{{ $class->teacher->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Criada em</dt>
                                    <dd class="text-sm text-gray-900">{{ $class->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Estatísticas</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_students'] }}</div>
                                    <div class="text-sm text-blue-800">Alunos</div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_assessments'] }}</div>
                                    <div class="text-sm text-green-800">Avaliações</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gerenciar Alunos -->
            @if(auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Alunos Matriculados</h3>
                            @if($availableStudents->count() > 0)
                                <button onclick="openAddStudentModal()" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Adicionar Aluno
                                </button>
                            @endif
                        </div>

                        @if($class->students->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nome
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Matriculado em
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($class->students as $student)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $student->email }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form method="POST" action="{{ route('classes.remove-student', [$class, $student]) }}" 
                                                          class="inline" onsubmit="return confirm('Tem certeza que deseja remover este aluno da turma?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900">
                                                            🗑️ Remover
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-500 text-lg mb-4">👥</div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhum aluno matriculado</h4>
                                <p class="text-gray-500 mb-4">Comece adicionando alunos à turma.</p>
                                @if($availableStudents->count() > 0)
                                    <button onclick="openAddStudentModal()" 
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        ➕ Adicionar Primeiro Aluno
                                    </button>
                                @else
                                    <p class="text-sm text-gray-400">Não há alunos disponíveis para adicionar.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Avaliações da Turma -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Avaliações</h3>
                        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                            <a href="{{ route('assessments.create') }}?class_id={{ $class->id }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Nova Avaliação
                            </a>
                        @endif
                    </div>

                    @if($class->assessments->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($class->assessments as $assessment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $assessment->title }}</h4>
                                    <div class="text-sm text-gray-500 space-y-1">
                                        <div>Status: 
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $assessment->status === 'published' ? 'bg-green-100 text-green-800' : 
                                                   ($assessment->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($assessment->status) }}
                                            </span>
                                        </div>
                                        <div>Questões: {{ $assessment->questions->count() }}</div>
                                        <div>Duração: {{ $assessment->duration_minutes }} min</div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('assessments.show', $assessment) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Ver detalhes →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg mb-4">📝</div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhuma avaliação criada</h4>
                            <p class="text-gray-500 mb-4">Crie avaliações para esta turma.</p>
                            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                                <a href="{{ route('assessments.create') }}?class_id={{ $class->id }}" 
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Criar Primeira Avaliação
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Adicionar Aluno -->
    @if($availableStudents->count() > 0 && (auth()->user()->isAdmin() || auth()->user()->id === $class->teacher_id))
        <div id="addStudentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Adicionar Aluno</h3>
                        <button onclick="closeAddStudentModal()" class="text-gray-400 hover:text-gray-600">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('classes.add-student', $class) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selecionar Aluno</label>
                            <select name="student_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Escolha um aluno</option>
                                @foreach($availableStudents as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAddStudentModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Adicionar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openAddStudentModal() {
            document.getElementById('addStudentModal').classList.remove('hidden');
        }
        
        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>