<?php

namespace App\Providers;

// use App\Helopers\Currency;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $loader = AliasLoader::getInstance();

        // Add your aliases
        // $loader->alias('Currency', \App\Helopers\Currency::class);

        //
    }
}
