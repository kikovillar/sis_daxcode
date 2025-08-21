<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ➕ Nova Disciplina
            </h2>
            
            <a href="{{ route('subjects.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('subjects.store') }}">
                        @csrf
                        
                        <!-- Nome da Disciplina -->
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Nome da Disciplina')" />
                            <x-text-input id="name" 
                                        name="name" 
                                        type="text" 
                                        class="mt-1 block w-full" 
                                        :value="old('name')" 
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
                                    placeholder="Descreva o conteúdo e objetivos da disciplina...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">
                                Máximo de 1000 caracteres. Esta descrição ajudará outros professores a entender o escopo da disciplina.
                            </p>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">ℹ️ Informações Importantes</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• A disciplina ficará disponível para todos os professores criarem questões e avaliações</li>
                                <li>• O nome da disciplina deve ser único no sistema</li>
                                <li>• Após criar, você poderá adicionar questões e criar avaliações para esta disciplina</li>
                                @if(auth()->user()->isAdmin())
                                    <li>• Como administrador, você pode editar ou excluir disciplinas posteriormente</li>
                                @endif
                            </ul>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('subjects.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            
                            <x-primary-button>
                                ✅ Criar Disciplina
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>