<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            👥
                        </span>
                        Gerenciar Alunos
                    </h2>
                    <p class="text-green-100 mt-1">{{ $class->name }} • Professor: {{ $class->teacher->name }}</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('classes.show', $class) }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>←</span>
                        <span>Voltar para Turma</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Dashboard de Estatísticas Moderno -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $class->students->count() }}</div>
                                <div class="text-blue-100 text-sm">Matriculados</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">👥</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-2">📊</span>
                            <span>Alunos na turma</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $availableStudents->count() }}</div>
                                <div class="text-green-100 text-sm">Disponíveis</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">➕</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-2">🎯</span>
                            <span>Para adicionar</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $transferClasses->count() }}</div>
                                <div class="text-purple-100 text-sm">Turmas</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">🔄</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-2">↔️</span>
                            <span>Para transferência</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold">{{ Str::limit($class->teacher->name, 12) }}</div>
                                <div class="text-yellow-100 text-sm">Professor</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">👨‍🏫</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-2">🎓</span>
                            <span>Responsável</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Adicionar Alunos -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-green-100 text-green-600 rounded-lg p-2 mr-3">➕</span>
                            Adicionar Alunos à Turma
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Selecione os alunos que deseja matricular nesta turma</p>
                    </div>
                    <div class="p-6">
                        
                        @if($availableStudents->count() > 0)
                            <form method="POST" action="{{ route('classes.add-multiple-students', $class) }}" id="addStudentsForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Selecionar Alunos ({{ $availableStudents->count() }} disponíveis)
                                        </label>
                                        <div class="space-x-2">
                                            <button type="button" onclick="selectAllAvailable()" 
                                                    class="text-sm text-blue-600 hover:text-blue-800">
                                                Selecionar Todos
                                            </button>
                                            <button type="button" onclick="deselectAllAvailable()" 
                                                    class="text-sm text-gray-600 hover:text-gray-800">
                                                Desmarcar Todos
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2">
                                        @foreach($availableStudents as $student)
                                            <label class="flex items-center space-x-2 hover:bg-gray-50 p-2 rounded">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                       class="available-student-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <div class="flex-1">
                                                    <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    ➕ Adicionar Alunos Selecionados
                                </button>
                            </form>
                        @else
                            <div class="text-center py-12">
                                <div class="bg-gradient-to-r from-gray-100 to-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                    <span class="text-3xl">👥</span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Todos os alunos já estão matriculados</h4>
                                <p class="text-gray-600">Não há alunos disponíveis para adicionar a esta turma no momento.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alunos Matriculados -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">👥</span>
                            Alunos Matriculados na Turma
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Gerencie os alunos que já estão matriculados nesta turma</p>
                    </div>
                    <div class="p-6">
                        
                        @if($class->students->count() > 0)
                            <form method="POST" action="{{ route('classes.remove-multiple-students', $class) }}" id="removeStudentsForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Alunos na Turma ({{ $class->students->count() }})
                                        </label>
                                        <div class="space-x-2">
                                            <button type="button" onclick="selectAllEnrolled()" 
                                                    class="text-sm text-red-600 hover:text-red-800">
                                                Selecionar Todos
                                            </button>
                                            <button type="button" onclick="deselectAllEnrolled()" 
                                                    class="text-sm text-gray-600 hover:text-gray-800">
                                                Desmarcar Todos
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2">
                                        @foreach($class->students as $student)
                                            <div class="flex items-center justify-between hover:bg-gray-50 p-2 rounded">
                                                <label class="flex items-center space-x-2 flex-1">
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                           class="enrolled-student-checkbox rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                    <div class="flex-1">
                                                        <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $student->email }} • 
                                                            Matriculado em {{ $student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('d/m/Y') : 'Data não informada' }}
                                                        </div>
                                                    </div>
                                                </label>
                                                
                                                @if($transferClasses->count() > 0)
                                                    <div class="ml-2">
                                                        <button type="button" 
                                                                onclick="openTransferModal({{ $student->id }}, '{{ $student->name }}')"
                                                                class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded"
                                                                title="Transferir para outra turma">
                                                            🔄
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        onclick="return confirm('Tem certeza que deseja remover os alunos selecionados desta turma?')"
                                        class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    ➖ Remover Alunos Selecionados
                                </button>
                            </form>
                        @else
                            <div class="text-center py-12">
                                <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                    <span class="text-3xl">📚</span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Turma ainda sem alunos</h4>
                                <p class="text-gray-600 mb-4">Esta turma ainda não possui alunos matriculados.</p>
                                @if($availableStudents->count() > 0)
                                    <p class="text-sm text-blue-600">👈 Use o painel ao lado para adicionar alunos</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Transferência -->
    @if($transferClasses->count() > 0)
        <div id="transferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">🔄 Transferir Aluno</h3>
                    
                    <form id="transferForm" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">
                                Transferir <strong id="studentName"></strong> para:
                            </p>
                            
                            <select name="target_class_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Selecione a turma de destino</option>
                                @foreach($transferClasses as $targetClass)
                                    <option value="{{ $targetClass->id }}">
                                        {{ $targetClass->name }} ({{ $targetClass->teacher->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="closeTransferModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                🔄 Transferir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function selectAllAvailable() {
            document.querySelectorAll('.available-student-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
        }
        
        function deselectAllAvailable() {
            document.querySelectorAll('.available-student-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
        
        function selectAllEnrolled() {
            document.querySelectorAll('.enrolled-student-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
        }
        
        function deselectAllEnrolled() {
            document.querySelectorAll('.enrolled-student-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
        
        function openTransferModal(studentId, studentName) {
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('transferForm').action = 
                '{{ route("classes.transfer-student", ["class" => $class->id, "student" => "__STUDENT_ID__"]) }}'
                .replace('__STUDENT_ID__', studentId);
            document.getElementById('transferModal').classList.remove('hidden');
        }
        
        function closeTransferModal() {
            document.getElementById('transferModal').classList.add('hidden');
        }
        
        // Fechar modal ao clicar fora dele
        document.getElementById('transferModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransferModal();
            }
        });
    </script>
</x-app-layout>