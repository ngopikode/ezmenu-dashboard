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

        // Set app.url dynamically based on the current request host
        if (!app()->runningInConsole()) {
            $url = request()->getSchemeAndHttpHost();
            config(['app.url' => $url]);
        }

        if ($subdomain = request()->route('subdomain')) {
            URL::defaults(['subdomain' => $subdomain]);
        }
    }
}
