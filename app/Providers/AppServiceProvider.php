<?php

namespace App\Providers;

use App\Helopers\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance('abilities', include base_path('data/abilities.php'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'order' => Order::class,
            'user' => User::class,
            'product' => Product::class,
        ]);

        Gate::before(function (User $user) {
            if ($user->is_admin) {
                return true;
            }
        });

        // Gate::define('is_admin',function(User $user) {
        //     if
        // });
        // class_alias(Currency::class, 'Currency');
    }
}
