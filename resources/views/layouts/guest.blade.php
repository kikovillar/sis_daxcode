<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $systemName = \App\Http\Controllers\SystemSettingsController::getSetting('system_name', 'Sistema de Avaliacao');
            $faviconPath = \App\Http\Controllers\SystemSettingsController::getSetting('favicon_path');
        @endphp
        <title>{{ $systemName }}</title>

        <!-- Favicon -->
        @if($faviconPath && !empty($faviconPath))
            <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $faviconPath) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="text-center">
                <a href="/" class="flex flex-col items-center">
                    @php
                        $logoPath = \App\Http\Controllers\SystemSettingsController::getSetting('logo_path');
                        $systemName = \App\Http\Controllers\SystemSettingsController::getSetting('system_name', 'Sistema de Avaliacao');
                    @endphp
                    
                    @if($logoPath && !empty($logoPath))
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $logoPath) }}" 
                                 alt="{{ $systemName }}" 
                                 class="max-h-20 w-auto">
                        </div>
                    @endif
                    
                    <span class="text-2xl font-bold text-gray-800">{{ $systemName }}</span>
                </a>
            </div>

            <div class="w-full xl:max-w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>