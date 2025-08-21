<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="text-center">
                        <div class="text-6xl mb-6">📝</div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-4">
                            Pronto para começar?
                        </h1>
                        <p class="text-lg text-gray-600 mb-8">
                            Você está prestes a iniciar a avaliação: <strong>{{ $assessment->title }}</strong>
                        </p>
                    </div>

                    <!-- Informações da Avaliação -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Informações da Avaliação</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <span class="text-blue-600 mr-2">⏱️</span>
                                <span><strong>Duração:</strong> {{ $assessment->duration_minutes }} minutos</span>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="text-green-600 mr-2">📊</span>
                                <span><strong>Pontuação:</strong> {{ $assessment->max_score }} pontos</span>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="text-purple-600 mr-2">❓</span>
                                <span><strong>Questões:</strong> {{ $assessment->questions->count() }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="text-orange-600 mr-2">📚</span>
                                <span><strong>Disciplina:</strong> {{ $assessment->subject->name }}</span>
                            </div>
                        </div>

                        @if($assessment->description)
                            <div class="mt-4">
                                <p class="text-gray-700">{{ $assessment->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Instruções -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">📖 Instruções Importantes</h3>
                        
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2 mt-1">•</span>
                                <span>Você terá <strong>{{ $assessment->duration_minutes }} minutos</strong> para completar a avaliação.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2 mt-1">•</span>
                                <span>Suas respostas serão <strong>salvas automaticamente</strong> a cada mudança.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2 mt-1">•</span>
                                <span>Você pode <strong>navegar entre as questões</strong> livremente durante o tempo disponível.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2 mt-1">•</span>
                                <span>A avaliação será <strong>finalizada automaticamente</strong> quando o tempo esgotar.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2 mt-1">•</span>
                                <span>Certifique-se de ter uma <strong>conexão estável com a internet</strong>.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 mr-2 mt-1">•</span>
                                <span>Não feche ou atualize a página durante a avaliação.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Verificação do Sistema -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">✅ Verificação do Sistema</h3>
                        
                        <div class="space-y-2">
                            <div class="flex items-center text-green-800">
                                <span class="text-green-600 mr-2">✓</span>
                                <span>Conexão com a internet: <strong>Ativa</strong></span>
                            </div>
                            <div class="flex items-center text-green-800">
                                <span class="text-green-600 mr-2">✓</span>
                                <span>Navegador compatível: <strong>Sim</strong></span>
                            </div>
                            <div class="flex items-center text-green-800">
                                <span class="text-green-600 mr-2">✓</span>
                                <span>JavaScript habilitado: <strong>Sim</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('student.assessments.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg">
                            ← Voltar
                        </a>
                        
                        <form method="POST" action="{{ route('student.assessment.take', $assessment) }}">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg"
                                    onclick="return confirm('Tem certeza que deseja iniciar a avaliação? O cronômetro começará imediatamente.')">
                                🚀 Iniciar Avaliação
                            </button>
                        </form>
                    </div>

                    <!-- Aviso Final -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500">
                            Ao clicar em "Iniciar Avaliação", você concorda em seguir as regras acadêmicas da instituição.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>