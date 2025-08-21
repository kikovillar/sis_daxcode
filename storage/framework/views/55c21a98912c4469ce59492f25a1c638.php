<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="text-center">
                <a href="/" class="flex flex-col items-center">
                    <?php
                        $logoPath = \App\Http\Controllers\SystemSettingsController::getSetting('logo_path');
                        $systemName = \App\Http\Controllers\SystemSettingsController::getSetting('system_name', 'Sistema de Avaliacao');
                    ?>
                    
                    <?php if($logoPath && !empty($logoPath)): ?>
                        <div class="mb-3">
                            <img src="<?php echo e(asset('storage/' . $logoPath)); ?>" 
                                 alt="<?php echo e($systemName); ?>" 
                                 class="max-h-20 w-auto">
                        </div>
                    <?php endif; ?>
                    
                    <span class="text-2xl font-bold text-gray-800"><?php echo e($systemName); ?></span>
                </a>
            </div>

            <div class="w-full xl:max-w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/layouts/guest.blade.php ENDPATH**/ ?>