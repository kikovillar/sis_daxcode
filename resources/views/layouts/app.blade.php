<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/sidebar.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="relative">
                <!-- Mobile sidebar overlay -->
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="sidebarOpen = false"
                     class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 lg:hidden"></div>

                <!-- Sidebar panel -->
                <div class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
                     :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
                    
                    <!-- Sidebar header -->
                    <div class="flex items-center justify-between h-16 px-6  bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            @php
                                $logoPath = \App\Http\Controllers\SystemSettingsController::getSetting('logo_path');
                                $systemName = \App\Http\Controllers\SystemSettingsController::getSetting('system_name', 'Sistema de Avaliacao');
                            @endphp
                            
                            @if($logoPath)
                                <img src="{{ asset('storage/' . $logoPath) }}" 
                                     alt="{{ $systemName }}" 
                                     class="h-8 w-auto">
                            @else
                                <span class="text-lg font-bold text-white">{{ $systemName }}</span>
                            @endif
                        </div>
                        
                        <!-- Close button for mobile -->
                        <button @click="sidebarOpen = false" 
                                class="lg:hidden text-white hover:text-gray-200 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="mt-8 px-4">
                        @include('layouts.sidebar-navigation')
                    </nav>

                    <!-- User info at bottom -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center space-x-3">
                            @if(Auth::user()->hasProfilePhoto())
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-300 flex-shrink-0">
                                    <img src="{{ Auth::user()->getProfilePhotoUrl() }}" 
                                         alt="{{ Auth::user()->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-sm">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ Auth::user()->role === 'admin' ? 'Administrador' : (Auth::user()->role === 'professor' ? 'Professor' : 'Aluno') }}
                                </p>
                            </div>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" 
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                    
                                    <a href="{{ route('profile.edit') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Perfil
                                    </a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex-1 flex flex-col lg:ml-0">
                <!-- Top bar -->
                <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
                    <div class="flex items-center justify-between h-16 px-4">
                        <button @click="sidebarOpen = true" 
                                class="text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <span class="text-lg font-semibold text-gray-900">
                            {{ $systemName ?? 'Sistema de Avaliacao' }}
                        </span>
                        
                        <!-- User avatar for mobile -->
                        <div class="flex items-center">
                            @if(Auth::user()->hasProfilePhoto())
                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-gray-300">
                                    <img src="{{ Auth::user()->getProfilePhotoUrl() }}" 
                                         alt="{{ Auth::user()->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-8 h-8 flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </header>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Alerts -->
        <div x-data="{ show: false, message: '', type: 'info' }" 
             x-on:show-alert.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 5000)"
             x-show="show" 
             x-transition
             class="fixed top-4 right-4 z-50">
            <div :class="{
                'bg-blue-500': type === 'info',
                'bg-green-500': type === 'success', 
                'bg-red-500': type === 'error',
                'bg-yellow-500': type === 'warning'
            }" class="text-white px-6 py-4 rounded-lg shadow-lg">
                <span x-text="message"></span>
            </div>
        </div>
    </body>
</html>