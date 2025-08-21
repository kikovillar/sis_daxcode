<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⚙️ Configuracoes do Sistema
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Configurações Visuais -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">🎨 Aparencia do Sistema</h3>
                    
                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nome do Sistema -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="system_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome do Sistema
                                </label>
                                <input type="text" id="system_name" name="system_name" 
                                       class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       value="{{ old('system_name', $settings['system_name']) }}"
                                       placeholder="Sistema de Avaliacao">
                            </div>
                            
                            <div>
                                <label for="system_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descricao
                                </label>
                                <input type="text" id="system_description" name="system_description" 
                                       class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       value="{{ old('system_description', $settings['system_description']) }}"
                                       placeholder="Plataforma de avaliacoes educacionais">
                            </div>
                        </div>
                        
                        <!-- Logo do Sistema -->
                        <div class="border-t pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">🖼️ Logo do Sistema</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Upload da Logo -->
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nova Logo
                                    </label>
                                    <input type="file" id="logo" name="logo" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           onchange="previewLogo(this)">
                                    <p class="text-sm text-gray-500 mt-1">
                                        Formatos: JPG, PNG, GIF, SVG. Tamanho máximo: 2MB
                                    </p>
                                </div>
                                
                                <!-- Preview da Logo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Logo Atual
                                    </label>
                                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                        @if($settings['logo_path'] && !empty($settings['logo_path']))
                                            <img id="currentLogo" src="{{ asset('storage/' . $settings['logo_path']) }}" 
                                                 alt="Logo do Sistema" class="max-h-16 w-auto">
                                            <div class="mt-2">
                                                <a href="{{ route('admin.settings.reset-logo') }}" 
                                                   class="text-sm text-red-600 hover:text-red-800"
                                                   onclick="return confirm('Tem certeza que deseja resetar a logo?')">
                                                    🗑️ Resetar para padrão
                                                </a>
                                            </div>
                                        @else
                                            <div id="currentLogo" class="text-center py-4">
                                                <span class="text-2xl font-bold text-gray-600">{{ $settings['system_name'] }}</span>
                                                <p class="text-sm text-gray-500 mt-1">Logo padrão (texto)</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Preview da nova logo -->
                                    <div id="logoPreview" class="mt-4 hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Preview da Nova Logo
                                        </label>
                                        <div class="border border-gray-300 rounded-lg p-4 bg-blue-50">
                                            <img id="previewImg" src="" alt="Preview" class="max-h-16 w-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cores do Sistema -->
                        <div class="border-t pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">🎨 Cores do Sistema</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cor Primária
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" id="primary_color" name="primary_color" 
                                               class="h-10 w-16 border border-gray-300 rounded-md"
                                               value="{{ old('primary_color', $settings['primary_color']) }}">
                                        <input type="text" 
                                               class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                               value="{{ old('primary_color', $settings['primary_color']) }}"
                                               readonly>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cor Secundária
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" id="secondary_color" name="secondary_color" 
                                               class="h-10 w-16 border border-gray-300 rounded-md"
                                               value="{{ old('secondary_color', $settings['secondary_color']) }}">
                                        <input type="text" 
                                               class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                               value="{{ old('secondary_color', $settings['secondary_color']) }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Favicon -->
                        <div class="border-t pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">🔖 Favicon</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                        Novo Favicon
                                    </label>
                                    <input type="file" id="favicon" name="favicon" 
                                           accept="image/x-icon,image/png"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                    <p class="text-sm text-gray-500 mt-1">
                                        Formatos: ICO, PNG. Tamanho: 16x16 ou 32x32 pixels
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Favicon Atual
                                    </label>
                                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                        @if($settings['favicon_path'] && !empty($settings['favicon_path']))
                                            <img src="{{ asset('storage/' . $settings['favicon_path']) }}" 
                                                 alt="Favicon" class="w-8 h-8">
                                        @else
                                            <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">SA</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Texto do Rodapé -->
                        <div class="border-t pt-6">
                            <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Texto do Rodapé
                            </label>
                            <input type="text" id="footer_text" name="footer_text" 
                                   class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                   value="{{ old('footer_text', $settings['footer_text']) }}"
                                   placeholder="Sistema de Avaliacao - Todos os direitos reservados">
                        </div>
                        
                        <!-- Botões de Ação -->
                        <div class="flex justify-between items-center pt-6 border-t">
                            <div class="flex space-x-2">
                                <button type="button" onclick="previewSettings()" 
                                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    👁️ Preview
                                </button>
                                
                                <a href="{{ route('admin.settings.export') }}" 
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    📤 Exportar
                                </a>
                                
                                <button type="button" onclick="openImportModal()" 
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    📥 Importar
                                </button>
                            </div>
                            
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                💾 Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Configurações Avançadas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">⚙️ Configuracoes Avancadas</h3>
                    
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_registration" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ $settings['allow_registration'] ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Permitir auto-registro</span>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="maintenance_mode" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Modo de manutencao</span>
                                </label>
                            </div>
                            
                            <div>
                                <label for="max_file_size" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tamanho máximo de arquivo (KB)
                                </label>
                                <input type="number" id="max_file_size" name="max_file_size" 
                                       class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       value="{{ old('max_file_size', $settings['max_file_size']) }}"
                                       min="512" max="10240">
                            </div>
                            
                            <div>
                                <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">
                                    Timeout de sessão (minutos)
                                </label>
                                <input type="number" id="session_timeout" name="session_timeout" 
                                       class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       value="{{ old('session_timeout', $settings['session_timeout']) }}"
                                       min="5" max="1440">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                💾 Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Importação -->
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📥 Importar Configurações</h3>
                
                <form method="POST" action="{{ route('admin.settings.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="settings_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Arquivo de Configurações (JSON)
                            </label>
                            <input type="file" id="settings_file" name="settings_file" 
                                   accept=".json"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   required>
                        </div>
                        
                        <div class="bg-yellow-50 p-3 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                ⚠️ Apenas configurações seguras serão importadas. 
                                Logos e favicons não são incluídos por segurança.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeImportModal()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            📥 Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewLogo(input) {
            const file = input.files[0];
            const preview = document.getElementById('logoPreview');
            const previewImg = document.getElementById('previewImg');
            
            if (file) {
                // Verificar tamanho
                if (file.size > 2097152) {
                    alert('Arquivo muito grande! Tamanho máximo: 2MB');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
        
        function previewSettings() {
            const systemName = document.getElementById('system_name').value;
            const primaryColor = document.getElementById('primary_color').value;
            const secondaryColor = document.getElementById('secondary_color').value;
            
            const params = new URLSearchParams({
                system_name: systemName,
                primary_color: primaryColor,
                secondary_color: secondaryColor
            });
            
            window.open(`{{ route('admin.settings.preview') }}?${params}`, '_blank');
        }
        
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }
        
        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }
        
        // Sincronizar color picker com input text
        document.getElementById('primary_color').addEventListener('change', function() {
            this.nextElementSibling.value = this.value;
        });
        
        document.getElementById('secondary_color').addEventListener('change', function() {
            this.nextElementSibling.value = this.value;
        });
    </script>
</x-app-layout>