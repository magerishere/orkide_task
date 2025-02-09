<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
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
        $this->builderMacro();
    }

    private function builderMacro(): void
    {
        Builder::macro('freshQuery', function () {
            return $this->getModel()->newQuery();
        });
    }
}
