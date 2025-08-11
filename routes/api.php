<?php

use App\Enums\UserTypes;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\Auth\AccessTokenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthSocialiteController;
use App\Http\Controllers\Dashboard\NewsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Dashboard\CategoryController;
// use App\Http\Controllers\Dashboard\FetchAllDrivers;
use App\Http\Controllers\Dashboard\MemberController;
use App\Http\Controllers\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\PaymentMethodController as DashboardPaymentMethodController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\FavoriteController;
use App\Http\Controllers\Front\FeedbackController;
use App\Http\Controllers\Front\PaymentMethodController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\MarkNotificationToRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::prefix('/news')->controller(\App\Http\Controllers\Front\NewsController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{news}', 'show');
});

Route::prefix('/products')->controller(\App\Http\Controllers\Front\ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{product}/related', 'getRelatedProducts');
    Route::get('/{product}', 'show');
});

Route::prefix('subscription')->controller(SubscriberController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('/verify/{token}', 'verify');
    Route::put('/subscription/{subscriber}', 'update')->middleware('auth:sanctum');
});
// Route::post('/subscription', [SubscriberController::class, 'store']);

Route::get('/countries', CountryController::class);

Route::get('/site-feedbacks/check-eligibility', [\App\Http\Controllers\Front\SiteFeedbackController::class, 'checkEligibility']);

Route::post('/site-feedbacks', [\App\Http\Controllers\Front\SiteFeedbackController::class, 'store']);

Route::post('/contact', [ContactMessageController::class, 'store']);

Route::get('/search', SearchController::class);

Route::middleware(['auth:sanctum', 'verified', MarkNotificationToRead::class])->group(function () {

    Route::get('/auth/user', AuthController::class);

    Route::post('/checkout', CheckoutController::class);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);

    Route::get('/conversations/{id}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{id}/messages', [MessageController::class, 'store']);

    Route::get('/payment-methods', PaymentMethodController::class);

    Route::get('/cities', \App\Http\Controllers\Front\CityController::class);

    Route::post('/send-feedback/{product}', FeedbackController::class);

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

    Route::prefix('/my-favorite')->controller(FavoriteController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('/{product:id}', 'destroy');
    });
    Route::get('/notifications', NotificationController::class);
    // Dashboard Routes
    Route::prefix('/dashboard')->group(function () {

        Route::get('/', [DashboardController::class, 'index']);

        Route::get('/categories/all', [CategoryController::class, 'getAll']);

        // Route::get('/fetch-all-drivers', FetchAllDrivers::class);

        Route::prefix('/customers-feedback')->controller(\App\Http\Controllers\Dashboard\CustomerFeedbackController::class)->group(function () {
            Route::get('/', 'index');
            Route::put('/{feedback}/change-status', 'update');
            Route::delete('/{feedback}', 'destroy');
        });

        Route::prefix('/products')->controller(ProductController::class)
            ->group(function () {

                Route::get('/trash', 'trash');

                Route::put('/{id}/restore', 'restore');

                Route::delete('/{id}/force-delete', 'forceDelete');
            });

        Route::prefix('/orders')->controller(DashboardOrderController::class)
            ->group(function () {

                Route::get('/', 'index');

                Route::get('/{order:number}', 'show');

                Route::put('/{order:number}/assign-to-driver', 'assignDriver');

                Route::put('/{order}/{orderStatus}', 'update');
            });



        Route::controller(UserController::class)->prefix('/users/{userType}')->group(function () {
            Route::get('/', 'index');

            Route::post('/', 'store');

            Route::get('/fetch-all', 'fetchAllDrivers');

            Route::get('/{user:email}', 'show');

            Route::put('/{user:email}', 'update');

            Route::delete('/{user:email}', 'destroy');

        })->whereIn('userType', UserTypes::getAllTypes(UserTypes::Admin->value));

        Route::prefix('/conversations')
            ->middleware(['role:admin,moderator'])
            ->controller(App\Http\Controllers\Dashboard\ConversationController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{conversation}/messages', 'messages');
                Route::post('/{conversation}/messages', 'sendMessage');
            });

        Route::get('/contact', [ContactMessageController::class, 'index']);
        Route::get('/contact/{contactMessage}', [ContactMessageController::class, 'show']);
        Route::post('/contact/{contactMessage}/send-reply', [ContactMessageController::class, 'send']);

        Route::get('/abilities', AbilityController::class);

        Route::apiResource('/discounts', \App\Http\Controllers\Dashboard\DiscountController::class);

        Route::apiResource('services', ServiceController::class)->except(['store', 'destroy']);

        Route::get('/products/get-all', [ProductController::class, 'getAll']);
        Route::apiResource('/products', ProductController::class);

        Route::apiResource('/roles', RoleController::class);

        Route::get('/news/get-types', [NewsController::class, 'getNewsTypes']);

        Route::apiResource('/news', NewsController::class);

        Route::apiResource('/payment-methods', DashboardPaymentMethodController::class);

        Route::apiResource('/categories', CategoryController::class);

        Route::apiResource('/cities', CityController::class);
    });
});

// Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);
