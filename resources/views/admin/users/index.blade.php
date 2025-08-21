<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mx-4 -mt-4 px-4 pt-4 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                        <span class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            👥
                        </span>
                        Gerenciar Usuários
                    </h2>
                    <p class="text-indigo-100 mt-1">Administre todos os usuários do sistema</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.create') }}" 
                       class="bg-white bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white border-opacity-20">
                        <span>➕</span>
                        <span>Novo Usuário</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Dashboard de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $users->total() }}</div>
                                <div class="text-blue-100 text-sm">Total de Usuários</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">👥</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $users->where('role', 'professor')->count() }}</div>
                                <div class="text-green-100 text-sm">Professores</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">👨‍🏫</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $users->where('role', 'aluno')->count() }}</div>
                                <div class="text-purple-100 text-sm">Alunos</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">🎓</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-105 transition-all duration-200">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold">{{ $users->where('role', 'admin')->count() }}</div>
                                <div class="text-orange-100 text-sm">Administradores</div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-xl p-3">
                                <span class="text-2xl">⚙️</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros Modernos -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 rounded-lg p-2 mr-3">🔍</span>
                        Filtros e Busca
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">🔍 Buscar Usuário</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                   placeholder="Digite nome ou email..." 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">👤 Tipo de Usuário</label>
                            <select id="role" name="role" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">🔄 Todos os Tipos</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>⚙️ Administrador</option>
                                <option value="professor" {{ request('role') == 'professor' ? 'selected' : '' }}>👨‍🏫 Professor</option>
                                <option value="aluno" {{ request('role') == 'aluno' ? 'selected' : '' }}>🎓 Aluno</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                🔄
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Usuários -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Papel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($user->hasProfilePhoto())
                                                        <img src="{{ $user->getProfilePhotoUrl() }}" 
                                                             alt="{{ $user->name }}" 
                                                             class="h-10 w-10 rounded-full object-cover">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user->role === 'admin') bg-red-100 text-red-800
                                                @elseif($user->role === 'teacher') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                @if($user->role === 'admin') 👑 Admin
                                                @elseif($user->role === 'teacher') 👨‍🏫 Professor
                                                @else 👨‍🎓 Aluno @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->email_verified_at ? '✅ Ativo' : '❌ Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900" title="Visualizar">👁️</a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">✏️</a>
                                                <button onclick="openPhotoModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->getProfilePhotoUrl() }}', {{ $user->hasProfilePhoto() ? 'true' : 'false' }})" 
                                                        class="text-green-600 hover:text-green-900" title="Gerenciar Foto">📸</button>
                                                @if($user->id !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline"
                                                          onsubmit="return confirm('Tem certeza que deseja {{ $user->email_verified_at ? 'desativar' : 'ativar' }} este usuário?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                                title="{{ $user->email_verified_at ? 'Desativar usuário' : 'Ativar usuário' }}">
                                                            {{ $user->email_verified_at ? '🔒' : '🔓' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400" title="Não é possível alterar o status da própria conta">🔒</span>
                                                @endif
                                                @if($user->id !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" 
                                                          onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Nenhum usuário encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação Moderna -->
                    <x-pagination-info :paginator="$users" item-name="usuários" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Gerenciamento de Foto -->
    <div id="photoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Gerenciar Foto de Perfil</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Fechar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Foto Atual -->
                <div class="text-center mb-4">
                    <img id="currentPhoto" src="" alt="Foto atual" class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-gray-200">
                </div>

                <!-- Upload de Nova Foto -->
                <form id="uploadForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nova Foto</label>
                        <input type="file" name="profile_photo" accept="image/jpeg,image/jpg,image/png" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">JPG, JPEG ou PNG. Máximo 2MB.</p>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            📸 Atualizar Foto
                        </button>
                        <button type="button" id="removePhotoBtn" onclick="removePhoto()" 
                                class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                            🗑️ Remover Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentUserId = null;

        function openPhotoModal(userId, userName, photoUrl, hasPhoto) {
            currentUserId = userId;
            document.getElementById('modalTitle').textContent = `Foto de ${userName}`;
            document.getElementById('currentPhoto').src = photoUrl;
            document.getElementById('uploadForm').action = `/admin/users/${userId}/photo`;
            document.getElementById('removePhotoBtn').style.display = hasPhoto ? 'block' : 'none';
            document.getElementById('photoModal').classList.remove('hidden');
            
            // Reset do formulário
            document.getElementById('uploadForm').reset();
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
            currentUserId = null;
        }

        function removePhoto() {
            if (!currentUserId) return;
            
            if (confirm('Tem certeza que deseja remover a foto de perfil?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${currentUserId}/photo`;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';
                
                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Fechar modal ao clicar fora
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePhotoModal();
            }
        });
    </script>
</x-app-layout>