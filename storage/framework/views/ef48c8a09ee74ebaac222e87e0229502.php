<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        $systemName = \App\Http\Controllers\SystemSettingsController::getSetting('system_name', 'Sistema de Avaliacao');
        $faviconPath = \App\Http\Controllers\SystemSettingsController::getSetting('favicon_path');
    ?>
    <title><?php echo e($systemName); ?></title>
    
    <!-- Favicon -->
    <?php if($faviconPath && !empty($faviconPath)): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . $faviconPath)); ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
        <?php if(Route::has('login')): ?>
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Entrar</a>

                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registrar</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <div class="text-center">
                    <?php
                        $logoPath = \App\Http\Controllers\SystemSettingsController::getSetting('logo_path');
                        $systemName = \App\Http\Controllers\SystemSettingsController::getSetting('system_name', 'Sistema de Avaliacao');
                        $systemDescription = \App\Http\Controllers\SystemSettingsController::getSetting('system_description', 'Plataforma completa para avaliacoes online');
                    ?>
                    
                    <?php if($logoPath && !empty($logoPath)): ?>
                        <div class="mb-6">
                            <img src="<?php echo e(asset('storage/' . $logoPath)); ?>" 
                                 alt="<?php echo e($systemName); ?>" 
                                 class="mx-auto max-h-32 w-auto">
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="text-6xl font-bold text-gray-900 mb-4">
                        <?php echo e($systemName); ?>

                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        <?php echo e($systemDescription); ?>

                    </p>
                </div>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                <span class="text-2xl">👨‍🎓</span>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">Para Alunos</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                Acesse suas avaliacoes, realize provas com timer em tempo real, 
                                auto-salvamento de respostas e acompanhe seus resultados.
                            </p>
                        </div>
                    </div>

                    <div class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                <span class="text-2xl">👨‍🏫</span>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">Para Professores</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                Crie e gerencie avaliacoes, banco de questoes com multiplos tipos,
                                publique provas e acompanhe o desempenho dos alunos.
                            </p>
                        </div>
                    </div>

                    <div class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                <span class="text-2xl">⏱️</span>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">Timer em Tempo Real</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                Sistema de cronometro com auto-finalizacao, salvamento automatico
                                e alertas visuais para controle total do tempo.
                            </p>
                        </div>
                    </div>

                    <div class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                        <div>
                            <div class="h-16 w-16 bg-red-50 flex items-center justify-center rounded-full">
                                <span class="text-2xl">📊</span>
                            </div>

                            <h2 class="mt-6 text-xl font-semibold text-gray-900">Relatorios Detalhados</h2>

                            <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                Analytics completos, estatisticas de desempenho,
                                exportacao de resultados e dashboards interativos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-left">
                    <div class="flex items-center gap-4">
                        <a href="https://laravel.com" class="group inline-flex items-center hover:text-gray-700 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Laravel v<?php echo e(Illuminate\Foundation\Application::VERSION); ?> (PHP v<?php echo e(PHP_VERSION); ?>)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/welcome.blade.php ENDPATH**/ ?>