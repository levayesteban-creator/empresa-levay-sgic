<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Añadimos esta línea

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
        // Forzamos a que use la dirección local correcta para evitar pantallas en blanco
        URL::forceRootUrl('http://127.0.0.1:8000');

        // Mantenemos tu código original de Vite
        Vite::prefetch(concurrency: 3);
    }
}
