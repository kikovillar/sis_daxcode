<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⚙️ Configurações Avançadas: {{ $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('assessments.advanced.update', $assessment) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Configurações de Apresentação -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">🎯 Apresentação das Questões</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['shuffle_questions'] ?? false) ? 'checked' : '' }}>
                                    <label for="shuffle_questions" class="ml-2 text-sm text-gray-700">
                                        🔀 Embaralhar ordem das questões
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="shuffle_options" name="shuffle_options" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['shuffle_options'] ?? false) ? 'checked' : '' }}>
                                    <label for="shuffle_options" class="ml-2 text-sm text-gray-700">
                                        🎲 Embaralhar opções das questões
                                    </label>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="navigation_mode" class="block text-sm font-medium text-gray-700 mb-2">
                                        🧭 Modo de Navegação
                                    </label>
                                    <select id="navigation_mode" name="navigation_mode" 
                                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="free" {{ ($assessment->settings['navigation_mode'] ?? 'free') === 'free' ? 'selected' : '' }}>
                                            Livre - Pode navegar entre questões
                                        </option>
                                        <option value="sequential" {{ ($assessment->settings['navigation_mode'] ?? '') === 'sequential' ? 'selected' : '' }}>
                                            Sequencial - Deve responder em ordem
                                        </option>
                                        <option value="locked" {{ ($assessment->settings['navigation_mode'] ?? '') === 'locked' ? 'selected' : '' }}>
                                            Bloqueado - Não pode voltar após responder
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações de Feedback -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">💬 Feedback e Resultados</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['show_results_immediately'] ?? false) ? 'checked' : '' }}>
                                    <label for="show_results_immediately" class="ml-2 text-sm text-gray-700">
                                        ⚡ Mostrar resultados imediatamente
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="allow_review" name="allow_review" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['allow_review'] ?? false) ? 'checked' : '' }}>
                                    <label for="allow_review" class="ml-2 text-sm text-gray-700">
                                        👁️ Permitir revisão após conclusão
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="feedback_enabled" name="feedback_enabled" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['feedback_enabled'] ?? false) ? 'checked' : '' }}>
                                    <label for="feedback_enabled" class="ml-2 text-sm text-gray-700">
                                        📝 Habilitar feedback geral
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="question_feedback" name="question_feedback" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['question_feedback'] ?? false) ? 'checked' : '' }}>
                                    <label for="question_feedback" class="ml-2 text-sm text-gray-700">
                                        ❓ Mostrar explicações das questões
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações de Segurança -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">🔒 Segurança e Monitoramento</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="require_webcam" name="require_webcam" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['require_webcam'] ?? false) ? 'checked' : '' }}>
                                    <label for="require_webcam" class="ml-2 text-sm text-gray-700">
                                        📹 Exigir webcam ativa
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="prevent_copy_paste" name="prevent_copy_paste" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['prevent_copy_paste'] ?? false) ? 'checked' : '' }}>
                                    <label for="prevent_copy_paste" class="ml-2 text-sm text-gray-700">
                                        🚫 Prevenir copiar/colar
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="browser_lockdown" name="browser_lockdown" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['browser_lockdown'] ?? false) ? 'checked' : '' }}>
                                    <label for="browser_lockdown" class="ml-2 text-sm text-gray-700">
                                        🔐 Modo browser seguro
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="auto_submit" name="auto_submit" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ ($assessment->settings['auto_submit'] ?? false) ? 'checked' : '' }}>
                                    <label for="auto_submit" class="ml-2 text-sm text-gray-700">
                                        ⏰ Envio automático no fim do tempo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Configurações de Tempo e Tentativas -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">⏱️ Tempo e Tentativas</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="time_limit_warning" class="block text-sm font-medium text-gray-700 mb-2">
                                        ⚠️ Aviso de tempo (minutos antes do fim)
                                    </label>
                                    <input type="number" id="time_limit_warning" name="time_limit_warning" 
                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                           value="{{ $assessment->settings['time_limit_warning'] ?? 5 }}" 
                                           min="1" max="60">
                                </div>

                                <div>
                                    <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔄 Máximo de tentativas
                                    </label>
                                    <input type="number" id="max_attempts" name="max_attempts" 
                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                           value="{{ $assessment->settings['max_attempts'] ?? 1 }}" 
                                           min="1" max="10">
                                </div>

                                <div>
                                    <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-2">
                                        🎯 Nota mínima para aprovação (%)
                                    </label>
                                    <input type="number" id="passing_score" name="passing_score" 
                                           class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                           value="{{ $assessment->settings['passing_score'] ?? 60 }}" 
                                           min="0" max="100" step="0.1">
                                </div>
                            </div>
                        </div>

                        <!-- Certificado -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">🏆 Certificado</h3>
                            
                            <div>
                                <label for="certificate_template" class="block text-sm font-medium text-gray-700 mb-2">
                                    📜 Template do certificado
                                </label>
                                <select id="certificate_template" name="certificate_template" 
                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Sem certificado</option>
                                    <option value="basic" {{ ($assessment->settings['certificate_template'] ?? '') === 'basic' ? 'selected' : '' }}>
                                        Básico
                                    </option>
                                    <option value="formal" {{ ($assessment->settings['certificate_template'] ?? '') === 'formal' ? 'selected' : '' }}>
                                        Formal
                                    </option>
                                    <option value="modern" {{ ($assessment->settings['certificate_template'] ?? '') === 'modern' ? 'selected' : '' }}>
                                        Moderno
                                    </option>
                                </select>
                                <p class="text-sm text-gray-500 mt-1">
                                    Certificado será gerado automaticamente para alunos que atingirem a nota mínima
                                </p>
                            </div>
                        </div>

                        <!-- Preview das Configurações -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">👁️ Preview das Configurações</h4>
                            <div id="settingsPreview" class="text-sm text-blue-800">
                                <!-- Será preenchido via JavaScript -->
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-between pt-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('assessments.show', $assessment) }}" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    ← Voltar
                                </a>
                                
                                <a href="{{ route('assessments.preview', $assessment) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" 
                                   target="_blank">
                                    👁️ Preview
                                </a>
                            </div>
                            
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                💾 Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview das configurações em tempo real
        function updatePreview() {
            const preview = document.getElementById('settingsPreview');
            const settings = [];
            
            // Verificar configurações marcadas
            if (document.getElementById('shuffle_questions').checked) {
                settings.push('🔀 Questões embaralhadas');
            }
            if (document.getElementById('shuffle_options').checked) {
                settings.push('🎲 Opções embaralhadas');
            }
            if (document.getElementById('show_results_immediately').checked) {
                settings.push('⚡ Resultados imediatos');
            }
            if (document.getElementById('require_webcam').checked) {
                settings.push('📹 Webcam obrigatória');
            }
            if (document.getElementById('prevent_copy_paste').checked) {
                settings.push('🚫 Copiar/colar bloqueado');
            }
            if (document.getElementById('browser_lockdown').checked) {
                settings.push('🔐 Browser seguro');
            }
            
            const maxAttempts = document.getElementById('max_attempts').value;
            if (maxAttempts > 1) {
                settings.push(`🔄 ${maxAttempts} tentativas permitidas`);
            }
            
            const passingScore = document.getElementById('passing_score').value;
            if (passingScore) {
                settings.push(`🎯 Nota mínima: ${passingScore}%`);
            }
            
            const certificate = document.getElementById('certificate_template').value;
            if (certificate) {
                settings.push(`🏆 Certificado: ${certificate}`);
            }
            
            preview.innerHTML = settings.length > 0 
                ? settings.join(' • ') 
                : 'Configurações padrão aplicadas';
        }
        
        // Atualizar preview quando campos mudarem
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('change', updatePreview);
            });
            updatePreview(); // Preview inicial
        });
        
        // Validações
        document.querySelector('form').addEventListener('submit', function(e) {
            const webcam = document.getElementById('require_webcam').checked;
            const browserLockdown = document.getElementById('browser_lockdown').checked;
            
            if (webcam || browserLockdown) {
                if (!confirm('⚠️ Configurações de segurança avançadas foram ativadas. Certifique-se de que os alunos estão cientes dos requisitos técnicos. Continuar?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</x-app-layout>