<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✏️ Editar Disciplina: {{ $subject->name }}
            </h2>
            
            <div class="flex space-x-2">
                <a href="{{ route('subjects.show', $subject) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    👁️ Visualizar
                </a>
                <a href="{{ route('subjects.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('subjects.update', $subject) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nome da Disciplina -->
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Nome da Disciplina')" />
                            <x-text-input id="name" 
                                        name="name" 
                                        type="text" 
                                        class="mt-1 block w-full" 
                                        :value="old('name', $subject->name)" 
                                        required 
                                        autofocus 
                                        placeholder="Ex: Matemática, História, Português..." />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Descrição -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Descrição (Opcional)')" />
                            <textarea id="description" 
                                    name="description" 
                                    rows="4"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Descreva o conteúdo e objetivos da disciplina...">{{ old('description', $subject->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">
                                Máximo de 1000 caracteres. Esta descrição ajudará outros professores a entender o escopo da disciplina.
                            </p>
                        </div>

                        <!-- Estatísticas Atuais -->
                        <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                            <h4 class="font-medium text-yellow-900 mb-2">📊 Uso Atual da Disciplina</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="text-center p-2 bg-white rounded">
                                    <div class="text-lg font-bold text-blue-600">{{ $subject->questions()->count() }}</div>
                                    <div class="text-gray-600">Questões</div>
                                </div>
                                <div class="text-center p-2 bg-white rounded">
                                    <div class="text-lg font-bold text-green-600">{{ $subject->assessments()->count() }}</div>
                                    <div class="text-gray-600">Avaliações</div>
                                </div>
                            </div>
                            @if($subject->questions()->count() > 0 || $subject->assessments()->count() > 0)
                                <p class="text-yellow-800 text-xs mt-2">
                                    ⚠️ Esta disciplina está sendo utilizada. Alterações no nome podem afetar a organização do conteúdo.
                                </p>
                            @endif
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('subjects.show', $subject) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            
                            <x-primary-button>
                                ✅ Salvar Alterações
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>