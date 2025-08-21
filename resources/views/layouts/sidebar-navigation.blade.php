@if(auth()->user()->isAdmin())
    <!-- Admin Menu -->
    <div class="space-y-1">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📊</span>
            <span class="font-medium">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('classes.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition-all duration-200 group {{ request()->routeIs('classes.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">🏫</span>
            <span class="font-medium">Turmas</span>
            @if(request()->routeIs('classes.*'))
                <span class="ml-auto w-2 h-2 bg-green-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('assessments.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 group {{ request()->routeIs('assessments.index') || request()->routeIs('assessments.show') || request()->routeIs('assessments.edit') || request()->routeIs('assessments.create') ? 'bg-purple-50 text-purple-600 border-r-4 border-purple-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📝</span>
            <span class="font-medium">Todas Avaliações</span>
            @if(request()->routeIs('assessments.index') || request()->routeIs('assessments.show') || request()->routeIs('assessments.edit') || request()->routeIs('assessments.create'))
                <span class="ml-auto w-2 h-2 bg-purple-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('questions.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200 group {{ request()->routeIs('questions.*') ? 'bg-yellow-50 text-yellow-600 border-r-4 border-yellow-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">❓</span>
            <span class="font-medium">Banco de Questões</span>
            @if(request()->routeIs('questions.*'))
                <span class="ml-auto w-2 h-2 bg-yellow-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('subjects.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 group {{ request()->routeIs('subjects.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📚</span>
            <span class="font-medium">Disciplinas</span>
            @if(request()->routeIs('subjects.*'))
                <span class="ml-auto w-2 h-2 bg-indigo-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('assessment-questions.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition-all duration-200 group {{ request()->routeIs('assessment-questions.*') ? 'bg-orange-50 text-orange-600 border-r-4 border-orange-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">🔧</span>
            <span class="font-medium">Gerenciar Questões</span>
            @if(request()->routeIs('assessment-questions.*'))
                <span class="ml-auto w-2 h-2 bg-orange-600 rounded-full"></span>
            @endif
        </a>

        <!-- Admin Section -->
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="flex items-center px-4 mb-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Administração</span>
                <div class="ml-2 flex-1 h-px bg-gray-200"></div>
            </div>
            
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-red-600 border-r-4 border-red-600 shadow-sm' : '' }}">
                    <span class="mr-3 text-xl">👥</span>
                    <span class="font-medium">Usuários</span>
                    @if(request()->routeIs('admin.users.*'))
                        <span class="ml-auto w-2 h-2 bg-red-600 rounded-full"></span>
                    @endif
                </a>
                
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-gray-600 transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-gray-50 text-gray-600 border-r-4 border-gray-600 shadow-sm' : '' }}">
                    <span class="mr-3 text-xl">⚙️</span>
                    <span class="font-medium">Configurações</span>
                    @if(request()->routeIs('admin.settings.*'))
                        <span class="ml-auto w-2 h-2 bg-gray-600 rounded-full"></span>
                    @endif
                </a>
                
                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition-all duration-200 group {{ request()->routeIs('admin.reports.*') ? 'bg-orange-50 text-orange-600 border-r-4 border-orange-600 shadow-sm' : '' }}">
                    <span class="mr-3 text-xl">📈</span>
                    <span class="font-medium">Relatórios</span>
                    @if(request()->routeIs('admin.reports.*'))
                        <span class="ml-auto w-2 h-2 bg-orange-600 rounded-full"></span>
                    @endif
                </a>
            </div>
        </div>
    </div>

@elseif(auth()->user()->isTeacher())
    <!-- Teacher Menu -->
    <div class="space-y-1">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📊</span>
            <span class="font-medium">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('classes.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition-all duration-200 group {{ request()->routeIs('classes.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">🏫</span>
            <span class="font-medium">Minhas Turmas</span>
            @if(request()->routeIs('classes.*'))
                <span class="ml-auto w-2 h-2 bg-green-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('assessments.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all duration-200 group {{ request()->routeIs('assessments.index') || request()->routeIs('assessments.show') || request()->routeIs('assessments.edit') || request()->routeIs('assessments.create') ? 'bg-purple-50 text-purple-600 border-r-4 border-purple-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📝</span>
            <span class="font-medium">Avaliações</span>
            @if(request()->routeIs('assessments.index') || request()->routeIs('assessments.show') || request()->routeIs('assessments.edit') || request()->routeIs('assessments.create'))
                <span class="ml-auto w-2 h-2 bg-purple-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('questions.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200 group {{ request()->routeIs('questions.*') ? 'bg-yellow-50 text-yellow-600 border-r-4 border-yellow-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">❓</span>
            <span class="font-medium">Banco de Questões</span>
            @if(request()->routeIs('questions.*'))
                <span class="ml-auto w-2 h-2 bg-yellow-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('subjects.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 group {{ request()->routeIs('subjects.*') ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📚</span>
            <span class="font-medium">Disciplinas</span>
            @if(request()->routeIs('subjects.*'))
                <span class="ml-auto w-2 h-2 bg-indigo-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('assessment-questions.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition-all duration-200 group {{ request()->routeIs('assessment-questions.*') ? 'bg-orange-50 text-orange-600 border-r-4 border-orange-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">🔧</span>
            <span class="font-medium">Gerenciar Questões</span>
            @if(request()->routeIs('assessment-questions.*'))
                <span class="ml-auto w-2 h-2 bg-orange-600 rounded-full"></span>
            @endif
        </a>

        <!-- Quick Actions -->
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="flex items-center px-4 mb-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ações Rápidas</span>
                <div class="ml-2 flex-1 h-px bg-gray-200"></div>
            </div>
            
            <div class="space-y-1">
                <a href="{{ route('assessments.create') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all duration-200 group {{ request()->routeIs('assessments.create') ? 'bg-emerald-50 text-emerald-600 border-r-4 border-emerald-600 shadow-sm' : '' }}">
                    <span class="mr-3 text-xl">➕</span>
                    <span class="font-medium">Nova Avaliação</span>
                    @if(request()->routeIs('assessments.create'))
                        <span class="ml-auto w-2 h-2 bg-emerald-600 rounded-full"></span>
                    @endif
                </a>
                
                <a href="{{ route('classes.create') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-teal-50 hover:text-teal-600 transition-all duration-200 group {{ request()->routeIs('classes.create') ? 'bg-teal-50 text-teal-600 border-r-4 border-teal-600 shadow-sm' : '' }}">
                    <span class="mr-3 text-xl">🏗️</span>
                    <span class="font-medium">Nova Turma</span>
                    @if(request()->routeIs('classes.create'))
                        <span class="ml-auto w-2 h-2 bg-teal-600 rounded-full"></span>
                    @endif
                </a>
            </div>
        </div>
    </div>

@else
    <!-- Student Menu -->
    <div class="space-y-1">
        <a href="{{ route('student.assessments.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 group {{ request()->routeIs('student.assessments.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📝</span>
            <span class="font-medium">Minhas Avaliações</span>
            @if(request()->routeIs('student.assessments.*'))
                <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
            @endif
        </a>
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-600 border-r-4 border-green-600 shadow-sm' : '' }}">
            <span class="mr-3 text-xl">📊</span>
            <span class="font-medium">Meu Desempenho</span>
            @if(request()->routeIs('dashboard'))
                <span class="ml-auto w-2 h-2 bg-green-600 rounded-full"></span>
            @endif
        </a>

        <!-- Student Info Section -->
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-100">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-10 h-10 flex items-center justify-center">
                        <span class="text-white font-bold text-sm">📚</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Área do Estudante</h4>
                        <p class="text-xs text-gray-600">Acesse suas avaliações e acompanhe seu progresso</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif