<?php

namespace App\Providers;

use App\Services\LocaleResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LocaleResolver::class, fn (): LocaleResolver => new LocaleResolver(
            array_keys(config('voice_flow.supported_locales')),
            config('voice_flow.default_locale'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
