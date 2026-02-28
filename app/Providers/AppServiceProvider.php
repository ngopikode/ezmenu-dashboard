<?php

namespace App\Providers;

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
        if (!app()->runningInConsole()) {

            $host = request()->getHost();

            if (app()->environment('production')) {
                $url = "https://{$host}";
                app('url')->forceScheme('https');
            } else {
                $url = request()->getSchemeAndHttpHost();
            }

            config(['app.url' => $url]);
            app('url')->forceRootUrl($url);
        }
    }
}
