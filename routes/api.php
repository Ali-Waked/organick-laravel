<?php

use App\Enums\UserTypes;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\Auth\AccessTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthSocialiteController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\MemberController;
use App\Http\Controllers\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\PaymentMethodController as DashboardPaymentMethodController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\PaymentMethodController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::prefix('/blogs')->controller(\App\Http\Controllers\Front\BlogController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{blog}', 'show');
});

Route::prefix('/products')->controller(\App\Http\Controllers\Front\ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{product}', 'show');
    // Route::get('/{category}')
});

Route::post('/subscription', [SubscriberController::class, 'store']);

Route::get('/countries', CountryController::class);

Route::post('/contact', [ContactMessageController::class, 'send']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/auth/user', AuthController::class);

    Route::post('/checkout', CheckoutController::class);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::put('/subscription/{subscriptionStatus}', [SubscriberController::class, 'update']);

    Route::get('/payment-methods', PaymentMethodController::class);

    Route::prefix('/cart')->controller(CartController::class)->group(function () {

        Route::get('/', 'index');

        Route::post('/', 'store');

        Route::delete('/', 'destroy');

        Route::delete('/{cartItem}', 'removeItem');

        Route::put('/{cartItem}', 'update');

        Route::get('/{cartItem}', 'show');
    });

    Route::prefix('/profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index');
        Route::patch('/', 'update');
    });

    // Dashboard Routes
    Route::prefix('/dashboard')->group(function () {

        Route::get('/', [DashboardController::class, 'index']);

        Route::get('/categories/all', [CategoryController::class, 'getAll']);

        Route::prefix('/products')->controller(ProductController::class)
            ->group(function () {

                Route::get('/trash', 'trash');

                Route::put('/{id}/restore', 'restore');

                Route::delete('/{id}/force-delete', 'forceDelete');
            });

        Route::prefix('/orders')->controller(DashboardOrderController::class)
            ->group(function () {

                Route::get('/',  'index');

                Route::get('/{order:number}',  'show');

                Route::put('/{order}/{orderStatus}',  'update');
            });



        Route::controller(UserController::class)->prefix('/users/{userType}')->group(function () {
            Route::get('/',  'index');

            Route::post('/',  'store');

            Route::get('/{user:email}', 'show');

            Route::put('/{user:email}', 'update');

            Route::delete('/{user:email}', 'destroy');
        })->whereIn('userType', UserTypes::getAllTypes(UserTypes::Admin->value));


        Route::get('/abilities', AbilityController::class);

        Route::get('/notifications', NotificationController::class);

        Route::apiResource('services', ServiceController::class)->except(['store', 'destroy']);


        Route::apiResource('/products', ProductController::class);

        Route::apiResource('/roles', RoleController::class);

        Route::apiResource('/blogs', BlogController::class);

        Route::apiResource('/payment-methods', DashboardPaymentMethodController::class);

        Route::apiResource('/categories', CategoryController::class);

        Route::apiResource('/cities', CityController::class);
    });
});
