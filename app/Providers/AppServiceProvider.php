<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        if (!$this->app->environment('local')) {
            URL::forceScheme('https');
        }

        if (!app()->runningInConsole()) {
            config([
                'app.url' => (app()->environment('local') ? 'http' : 'https')
                    . '://' . request()->getHost()
            ]);
        }
    }
}
