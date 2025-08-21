<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Nova Questão
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('questions.store') }}" class="space-y-6" id="questionForm">
                        @csrf

                        <!-- Informações Básicas -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Informações Básicas</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="title" :value="__('Título da Questão')" />
                                    <x-text-input id="title" name="title" type="text" 
                                                class="mt-1 block w-full" 
                                                :value="old('title')" 
                                                required autofocus 
                                                placeholder="Ex: Soma de números inteiros" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="subject_id" :value="__('Disciplina')" />
                                    <select id="subject_id" name="subject_id" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                            required>
                                        <option value="">Selecione uma disciplina</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="type" :value="__('Tipo de Questão')" />
                                    <select id="type" name="type" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                            required onchange="toggleQuestionType()">
                                        <option value="">Selecione o tipo</option>
                                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>📝 Múltipla Escolha</option>
                                        <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>✅ Verdadeiro/Falso</option>
                                        <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>📄 Dissertativa</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="difficulty" :value="__('Dificuldade')" />
                                    <select id="difficulty" name="difficulty" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                            required>
                                        <option value="">Selecione a dificuldade</option>
                                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>😊 Fácil</option>
                                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>😐 Médio</option>
                                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>😰 Difícil</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="points" :value="__('Pontuação')" />
                                    <x-text-input id="points" name="points" type="number" 
                                                class="mt-1 block w-full" 
                                                :value="old('points', 1)" 
                                                min="0.1" max="100" step="0.1" required />
                                    <x-input-error :messages="$errors->get('points')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Conteúdo da Questão -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📝 Conteúdo</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="content" :value="__('Enunciado da Questão')" />
                                    <textarea id="content" name="content" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="4" 
                                            required
                                            placeholder="Digite o enunciado da questão...">{{ old('content') }}</textarea>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="explanation" :value="__('Explicação (Opcional)')" />
                                    <textarea id="explanation" name="explanation" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3" 
                                            placeholder="Explicação da resposta correta ou dicas para resolução...">{{ old('explanation') }}</textarea>
                                    <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Upload de Imagem -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">🖼️ Imagem da Questão (Opcional)</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="question_image" :value="__('Selecionar Imagem')" />
                                    <input type="file" id="question_image" name="question_image" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                           onchange="previewImage(this)">
                                    <p class="text-sm text-gray-500 mt-1">
                                        Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB
                                    </p>
                                    <x-input-error :messages="$errors->get('question_image')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="image_description" :value="__('Descrição da Imagem (Alt Text)')" />
                                    <x-text-input id="image_description" name="image_description" type="text" 
                                                class="mt-1 block w-full" 
                                                :value="old('image_description')" 
                                                placeholder="Descreva a imagem para acessibilidade..." />
                                    <p class="text-sm text-gray-500 mt-1">
                                        Importante para acessibilidade e leitores de tela
                                    </p>
                                    <x-input-error :messages="$errors->get('image_description')" class="mt-2" />
                                </div>

                                <!-- Preview da Imagem -->
                                <div id="imagePreview" class="hidden">
                                    <x-input-label :value="__('Preview da Imagem')" />
                                    <div class="mt-2 border border-gray-300 rounded-lg p-4 bg-gray-50">
                                        <img id="previewImg" src="" alt="Preview" class="max-w-full h-auto max-h-64 rounded-lg shadow-sm">
                                        <button type="button" onclick="removeImagePreview()" 
                                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            🗑️ Remover imagem
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opções para Múltipla Escolha -->
                        <div id="multipleChoiceOptions" class="border-b border-gray-200 pb-6" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📝 Opções de Resposta</h3>
                            
                            <div id="optionsContainer" class="space-y-3">
                                <!-- Opções serão adicionadas dinamicamente -->
                            </div>
                            
                            <div class="mt-4 flex space-x-2">
                                <button type="button" onclick="addOption()" 
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    ➕ Adicionar Opção
                                </button>
                                <button type="button" onclick="removeLastOption()" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    ➖ Remover Última
                                </button>
                            </div>
                            
                            <p class="text-sm text-gray-600 mt-2">
                                💡 Marque a opção correta e adicione pelo menos 2 opções.
                            </p>
                        </div>

                        <!-- Opções para Verdadeiro/Falso -->
                        <div id="trueFalseOptions" class="border-b border-gray-200 pb-6" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">✅ Resposta Correta</h3>
                            
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="correct_answer" value="1" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ old('correct_answer') == '1' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">✅ Verdadeiro</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="radio" name="correct_answer" value="0" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ old('correct_answer') == '0' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">❌ Falso</span>
                                </label>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('questions.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                ❌ Cancelar
                            </a>
                            
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                💾 Salvar Questão
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let optionCount = 0;

        function previewImage(input) {
            const file = input.files[0];
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (file) {
                // Verificar tamanho do arquivo (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('Arquivo muito grande! O tamanho máximo é 2MB.');
                    input.value = '';
                    return;
                }
                
                // Verificar tipo do arquivo
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipo de arquivo não permitido! Use JPG, PNG ou GIF.');
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

        function removeImagePreview() {
            document.getElementById('question_image').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('image_description').value = '';
        }

        function toggleQuestionType() {
            const type = document.getElementById('type').value;
            const multipleChoice = document.getElementById('multipleChoiceOptions');
            const trueFalse = document.getElementById('trueFalseOptions');
            
            // Esconder todas as seções
            multipleChoice.style.display = 'none';
            trueFalse.style.display = 'none';
            
            // Mostrar seção apropriada
            if (type === 'multiple_choice') {
                multipleChoice.style.display = 'block';
                if (optionCount === 0) {
                    addOption();
                    addOption();
                }
            } else if (type === 'true_false') {
                trueFalse.style.display = 'block';
            }
        }

        function addOption() {
            optionCount++;
            const container = document.getElementById('optionsContainer');
            const optionDiv = document.createElement('div');
            optionDiv.className = 'flex items-center space-x-2';
            optionDiv.id = `option-${optionCount}`;
            
            optionDiv.innerHTML = `
                <input type="checkbox" name="options[${optionCount-1}][is_correct]" value="1" 
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <input type="text" name="options[${optionCount-1}][content]" 
                       class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                       placeholder="Digite a opção ${optionCount}..." required>
                <button type="button" onclick="removeOption(${optionCount})" 
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">
                    🗑️
                </button>
            `;
            
            container.appendChild(optionDiv);
        }

        function removeOption(id) {
            const option = document.getElementById(`option-${id}`);
            if (option) {
                option.remove();
            }
        }

        function removeLastOption() {
            if (optionCount > 0) {
                const lastOption = document.getElementById(`option-${optionCount}`);
                if (lastOption) {
                    lastOption.remove();
                    optionCount--;
                }
            }
        }

        // Inicializar baseado no valor antigo (para casos de erro de validação)
        document.addEventListener('DOMContentLoaded', function() {
            const oldType = '{{ old("type") }}';
            if (oldType) {
                document.getElementById('type').value = oldType;
                toggleQuestionType();
                
                // Recriar opções antigas se existirem
                @if(old('options'))
                    @foreach(old('options') as $index => $option)
                        addOption();
                        document.querySelector(`input[name="options[${index}][content]"]`).value = '{{ $option["content"] ?? "" }}';
                        @if(isset($option['is_correct']) && $option['is_correct'])
                            document.querySelector(`input[name="options[${index}][is_correct]"]`).checked = true;
                        @endif
                    @endforeach
                @endif
            }
        });
    </script>
</x-app-layout>