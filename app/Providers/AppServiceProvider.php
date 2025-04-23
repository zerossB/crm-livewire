<?php

namespace App\Providers;

use App\Enums\Can;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::shouldBeStrict(!app()->isProduction());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        collect(Can::cases())->each(function (Can $permission) {
            Gate::define($permission->value, function ($user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });
        });
    }
}
