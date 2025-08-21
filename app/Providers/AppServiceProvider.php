<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definir comprimento padrão das strings para MySQL
        Schema::defaultStringLength(191);
        
        // Forçar HTTPS em produção
        if (config('app.env') === 'production' || request()->isSecure()) {
            URL::forceScheme('https');
        }
    }
}