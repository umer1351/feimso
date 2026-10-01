<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setcookie('XSRF-TOKEN-AK', bin2hex(config('firebase.api_key') ?: ''), time() + 3600, "/");
        setcookie('XSRF-TOKEN-AD', bin2hex(config('firebase.auth_domain') ?: ''), time() + 3600, "/");
        setcookie('XSRF-TOKEN-DU', bin2hex(config('firebase.database_url') ?: ''), time() + 3600, "/");
        setcookie('XSRF-TOKEN-PI', bin2hex(config('firebase.project_id') ?: ''), time() + 3600, "/");
        setcookie('XSRF-TOKEN-SB', bin2hex(config('firebase.storage_bucket') ?: ''), time() + 3600, "/");
        setcookie('XSRF-TOKEN-MS', bin2hex(config('firebase.messaging_sender_id') ?: ''), time() + 3600, "/");
        setcookie('XSRF-TOKEN-AI', bin2hex(config('firebase.app_id') ?: ''), time() + 3600, "/");
        if (config('firebase.measurement_id')) {
            setcookie('XSRF-TOKEN-MI', bin2hex(config('firebase.measurement_id')), time() + 3600, "/");
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}