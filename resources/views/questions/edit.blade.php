<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Questão: {{ $question->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('questions.update', $question) }}" class="space-y-6" id="questionForm">
                        @csrf
                        @method('PUT')

                        <!-- Informações Básicas -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Informações Básicas</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="title" :value="__('Título da Questão')" />
                                    <x-text-input id="title" name="title" type="text" 
                                                class="mt-1 block w-full" 
                                                :value="old('title', $question->title)" 
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
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="type" :value="__('Tipo de Questão')" />
                                    <select id="type" name="type" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-gray-100" 
                                            disabled>
                                        <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>📝 Múltipla Escolha</option>
                                        <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>✅ Verdadeiro/Falso</option>
                                        <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>📄 Dissertativa</option>
                                    </select>
                                    <input type="hidden" name="type" value="{{ $question->type }}">
                                    <p class="text-sm text-gray-500 mt-1">O tipo da questão não pode ser alterado após a criação.</p>
                                </div>

                                <div>
                                    <x-input-label for="difficulty" :value="__('Dificuldade')" />
                                    <select id="difficulty" name="difficulty" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                            required>
                                        <option value="">Selecione a dificuldade</option>
                                        <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>😊 Fácil</option>
                                        <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>😐 Médio</option>
                                        <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>😰 Difícil</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="points" :value="__('Pontuação')" />
                                    <x-text-input id="points" name="points" type="number" 
                                                class="mt-1 block w-full" 
                                                :value="old('points', $question->points)" 
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
                                            placeholder="Digite o enunciado da questão...">{{ old('content', $question->content) }}</textarea>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="explanation" :value="__('Explicação (Opcional)')" />
                                    <textarea id="explanation" name="explanation" 
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3" 
                                            placeholder="Explicação da resposta correta ou dicas para resolução...">{{ old('explanation', $question->explanation) }}</textarea>
                                    <x-input-error :messages="$errors->get('explanation')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Gerenciamento de Imagem -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">🖼️ Imagem da Questão</h3>
                            
                            @if($question->image_path)
                                <!-- Imagem Atual -->
                                <div class="mb-6" id="currentImage">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">Imagem Atual</h4>
                                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                        <img src="{{ asset('storage/' . $question->image_path) }}" 
                                             alt="{{ $question->image_description ?? 'Imagem da questão' }}"
                                             class="max-w-full h-auto max-h-64 rounded-lg shadow-sm">
                                        @if($question->image_description)
                                            <p class="text-sm text-gray-600 mt-2">
                                                <strong>Descrição atual:</strong> {{ $question->image_description }}
                                            </p>
                                        @endif
                                        <div class="mt-3 flex space-x-2">
                                            <button type="button" onclick="showImageUpload()" 
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                🔄 Trocar Imagem
                                            </button>
                                            <button type="button" onclick="removeCurrentImage()" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                🗑️ Remover Imagem
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload de Nova Imagem -->
                            <div class="space-y-4" id="imageUploadSection" style="{{ $question->image_path ? 'display: none;' : '' }}">
                                <div>
                                    <x-input-label for="question_image" :value="__('Selecionar Nova Imagem')" />
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
                                                :value="old('image_description', $question->image_description)" 
                                                placeholder="Descreva a imagem para acessibilidade..." />
                                    <p class="text-sm text-gray-500 mt-1">
                                        Importante para acessibilidade e leitores de tela
                                    </p>
                                    <x-input-error :messages="$errors->get('image_description')" class="mt-2" />
                                </div>

                                <!-- Preview da Nova Imagem -->
                                <div id="imagePreview" class="hidden">
                                    <x-input-label :value="__('Preview da Nova Imagem')" />
                                    <div class="mt-2 border border-gray-300 rounded-lg p-4 bg-gray-50">
                                        <img id="previewImg" src="" alt="Preview" class="max-w-full h-auto max-h-64 rounded-lg shadow-sm">
                                        <button type="button" onclick="removeImagePreview()" 
                                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            🗑️ Cancelar nova imagem
                                        </button>
                                    </div>
                                </div>
                                
                                @if($question->image_path)
                                    <button type="button" onclick="hideImageUpload()" 
                                            class="text-sm text-gray-600 hover:text-gray-800">
                                        ❌ Cancelar alteração
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Campo hidden para remoção -->
                            <input type="hidden" id="remove_image" name="remove_image" value="0">
                            </div>
                        </div>

                        <!-- Opções para Múltipla Escolha -->
                        @if($question->type === 'multiple_choice')
                            <div id="multipleChoiceOptions" class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">📝 Opções de Resposta</h3>
                                
                                <div id="optionsContainer" class="space-y-3">
                                    @foreach($question->options as $index => $option)
                                        <div class="flex items-center space-x-2" id="option-{{ $index + 1 }}">
                                            <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1" 
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                   {{ $option->is_correct ? 'checked' : '' }}>
                                            <input type="text" name="options[{{ $index }}][content]" 
                                                   class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                                   placeholder="Digite a opção {{ $index + 1 }}..." 
                                                   value="{{ old('options.' . $index . '.content', $option->content) }}" required>
                                            <button type="button" onclick="removeOption({{ $index + 1 }})" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">
                                                🗑️
                                            </button>
                                        </div>
                                    @endforeach
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
                                    💡 Marque a opção correta e mantenha pelo menos 2 opções.
                                </p>
                            </div>
                        @endif

                        <!-- Opções para Verdadeiro/Falso -->
                        @if($question->type === 'true_false')
                            <div id="trueFalseOptions" class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">✅ Resposta Correta</h3>
                                
                                @php
                                    $correctOption = $question->options->where('is_correct', true)->first();
                                    $isTrue = $correctOption && $correctOption->content === 'Verdadeiro';
                                @endphp
                                
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="correct_answer" value="1" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ old('correct_answer', $isTrue ? '1' : '0') == '1' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">✅ Verdadeiro</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="correct_answer" value="0" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ old('correct_answer', $isTrue ? '1' : '0') == '0' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">❌ Falso</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Status de Uso -->
                        @if($question->assessments->count() > 0)
                            <div class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">⚠️ Aviso de Uso</h3>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <span class="text-yellow-400 text-xl">⚠️</span>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-yellow-800">
                                                Esta questão está sendo usada em {{ $question->assessments->count() }} avaliação(ões)
                                            </h4>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                Alterações podem afetar avaliações existentes e resultados de alunos.
                                            </p>
                                            <div class="mt-2">
                                                <ul class="text-sm text-yellow-700 list-disc list-inside">
                                                    @foreach($question->assessments as $assessment)
                                                        <li>{{ $assessment->title }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Botões de Ação -->
                        <div class="flex items-center justify-end space-x-4 pt-6">
                            <a href="{{ route('questions.show', $question) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                ❌ Cancelar
                            </a>
                            
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                💾 Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let optionCount = {{ $question->options->count() }};

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
        }

        function showImageUpload() {
            document.getElementById('imageUploadSection').style.display = 'block';
            document.getElementById('remove_image').value = '0';
        }

        function hideImageUpload() {
            document.getElementById('imageUploadSection').style.display = 'none';
            document.getElementById('question_image').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('remove_image').value = '0';
        }

        function removeCurrentImage() {
            if (confirm('Tem certeza que deseja remover a imagem atual?')) {
                document.getElementById('remove_image').value = '1';
                document.getElementById('currentImage').style.display = 'none';
                document.getElementById('imageUploadSection').style.display = 'block';
                
                // Limpar descrição se estiver removendo a imagem
                document.getElementById('image_description').value = '';
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
            if (optionCount > 2) { // Manter pelo menos 2 opções
                const lastOption = document.getElementById(`option-${optionCount}`);
                if (lastOption) {
                    lastOption.remove();
                    optionCount--;
                }
            }
        }
    </script>
</x-app-layout>