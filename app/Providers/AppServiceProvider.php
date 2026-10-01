<?php

namespace App\Providers;

use App\Firestore\Access;
use App\Firestore\Engine;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Access::class);
        $this->app->singleton(Engine::class);
    }

    public function boot()
    {
    }
}
