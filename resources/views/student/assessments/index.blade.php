<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Minhas Avaliações - Sistema de Avaliação</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">📝 Minhas Avaliações</h1>
                    <p class="text-gray-600">Gerencie suas provas e acompanhe seu progresso</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Olá, {{ auth()->user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        🏠 Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                            🚪 Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Avaliações Disponíveis -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">🎯 Avaliações Disponíveis</h3>
                    
                    @if($availableAssessments->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($availableAssessments as $assessment)
                                @php
                                    $attempt = $assessment->studentAssessments->first();
                                    $hasAttempt = $attempt !== null;
                                    $maxAttempts = $assessment->settings['max_attempts'] ?? 1;
                                    $currentAttempts = $assessment->studentAssessments->count();
                                    $canRetake = $currentAttempts < $maxAttempts;
                                    $timeLeft = $assessment->closes_at->diffForHumans();
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-semibold text-gray-900">{{ $assessment->title }}</h4>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            Disponível
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                                        <p><strong>📚 Disciplina:</strong> {{ $assessment->subject->name }}</p>
                                        <p><strong>⏱️ Duração:</strong> {{ $assessment->duration_minutes }} minutos</p>
                                        <p><strong>🏆 Pontuação:</strong> {{ $assessment->max_score }} pontos</p>
                                        <p><strong>📅 Prazo:</strong> {{ $timeLeft }}</p>
                                        <p><strong>❓ Questões:</strong> {{ $assessment->questions->count() }}</p>
                                        
                                        @if($maxAttempts > 1)
                                            <p><strong>🔄 Tentativas:</strong> {{ $currentAttempts }}/{{ $maxAttempts }}</p>
                                        @endif
                                    </div>
                                    
                                    @if($assessment->description)
                                        <p class="text-sm text-gray-700 mb-4">{{ Str::limit($assessment->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="flex justify-between items-center">
                                        @if($hasAttempt && $attempt->status === 'in_progress')
                                            <a href="{{ route('student.assessments.take', $attempt) }}" 
                                               class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                ▶️ Continuar
                                            </a>
                                        @elseif($canRetake)
                                            <a href="{{ route('student.assessments.start', $assessment) }}" 
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm"
                                               onclick="return confirm('Tem certeza que deseja iniciar esta avaliação?')">
                                                🚀 {{ $hasAttempt ? 'Nova Tentativa' : 'Iniciar' }}
                                            </a>
                                        @else
                                            <span class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded text-sm cursor-not-allowed">
                                                🔒 Limite Atingido
                                            </span>
                                        @endif
                                        
                                        @if($hasAttempt && $attempt->status === 'completed')
                                            <a href="{{ route('student.assessments.result', $attempt) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                📊 Ver Resultado
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">📚</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma avaliação disponível</h3>
                            <p class="text-gray-500">
                                Não há avaliações abertas no momento. Verifique novamente mais tarde.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Avaliações Concluídas -->
            @if($completedAssessments->count() > 0)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">✅ Avaliações Concluídas</h3>
                        
                        <div class="space-y-4">
                            @foreach($completedAssessments as $assessment)
                                @php
                                    $attempt = $assessment->studentAssessments->first();
                                    $percentage = ($attempt->score / $assessment->max_score) * 100;
                                    $passingScore = $assessment->settings['passing_score'] ?? 60;
                                    $passed = $percentage >= $passingScore;
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="font-semibold text-gray-900">{{ $assessment->title }}</h4>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $passed ? '✅ Aprovado' : '❌ Reprovado' }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">Disciplina:</span><br>
                                                    {{ $assessment->subject->name }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Nota:</span><br>
                                                    <span class="text-lg font-bold {{ $passed ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ number_format($attempt->score, 1) }}/{{ $assessment->max_score }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Percentual:</span><br>
                                                    <span class="text-lg font-bold {{ $passed ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ number_format($percentage, 1) }}%
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="font-medium">Concluída em:</span><br>
                                                    {{ $attempt->finished_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4 flex flex-col space-y-2">
                                            <a href="{{ route('student.assessments.result', $attempt) }}" 
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm text-center">
                                                📊 Detalhes
                                            </a>
                                            
                                            @if($passed && ($assessment->settings['certificate_template'] ?? false))
                                                <a href="{{ route('student.certificate', $attempt) }}" 
                                                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm text-center">
                                                    🏆 Certificado
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>