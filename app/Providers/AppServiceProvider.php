<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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

        if (!app()->runningInConsole() && app()->environment('production')) {

            $host = request()->getHost();
            $url = "https://$host";

            config(['app.url' => $url]);

            app('url')->forceRootUrl($url);
            app('url')->forceScheme('https');
        }
    }
}
