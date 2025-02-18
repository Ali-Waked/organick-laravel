<?php

use App\Enums\CategoryStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Enums\UserTypes;
use App\Events\CategoryCreated;
use App\Events\OrderCreated;
use App\Helopers\Currency;
use App\Http\Controllers\AuthSocialiteController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyConverterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriberController;
use App\Mail\SendContactMessage;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Services\PhotoroomService;
use App\Notifications\SubscriptionNotification;
use App\Models\User;
use App\Models\Subscription;
use App\PaymentGateways\PaymentGatewayFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;



Route::middleware('guest:sanctum')->group(function () {
    Route::get('/auth/{driver}/redirect', [AuthSocialiteController::class, 'redirect']);
    Route::get('/auth/{driver}/callback', [AuthSocialiteController::class, 'callback']);
});

Route::view('/', 'welcome');
Route::get('/send-mail', function () {
    // $user = User::where('id', 1)->first();
    // $subscriber = Subscription::where('id', 1)->first();
    // // dd(asset('logo.svg'));
    // Notification::send($user, new SubscriptionNotification($subscriber));
    $category = Category::query()->first();
    $order = Order::query()->first();
    // CategoryCreated::dispatch($category);
    event(new CategoryCreated($category));
    event(new OrderCreated($order));
    // dd('hi');
    return redirect()->to('/');
});


Route::post('/logout', function () {;
});

Route::get('/test', function (Request $request) {
    // return Order::with('customer:id,email')->withSum('items as total_price', 'price')
    //     ->whereRelation('customer', 'email', 'like', '%ali')->dd();
    // $name = 'ali';
    // dd(isset($name));
    // dd(Order::with('items')->select('*')->total()->get());
    // echo '<pre />';
    // $products = Product::with('category')->leftJoin('categories', 'categories.id', '=', 'products.id')->orderBy('categories.name')->select(['products.*']);

    // foreach ($products as $product) {
    //     print_r($product);
    // }
    // $order = Order::query()->first();
    // // dd($order->items->pluck('price', 'quantity')->toArray());
    // dd($order->items->map(function ($item) {
    //     return $item->only(['price', 'quantity']);
    // })->toArray());
    // ->selectSub(function ($query) {
    //     $query->select(DB::raw('SUM(quantity * price)'))
    //         ->from('order_items')
    //         ->whereColumn('order_items.order_id', 'orders.id');
    // }, 'total_price')

    // $collect = collect(['ali' => '23', 'waked' =>  'o3o' ]);
    // dd($collect?->omer ?? null);
    // dd(Order::whereLike('name', '%name')->whereNot('id', 2)->dd());
    dd(User::find(1)->first()->roles);
});
Route::get('/test', function () {
    $data = ['name' => 'Ali Waked', 'message' => '', 'email' => '', 'company' => '', 'subject' => 'Welcome to Organick! 🌿'];
    Mail::send(new SendContactMessage($data));
});
Route::get('/checkout', function (Request $request) {
    $order = Order::query()->first();
    // dd(PaymentMethods::tryFrom($request->payment_method));
    // dd($order, Str::studly('stripe_payment_type'));
    // dd(PaymentMethod::query()->first()->options);
    // $arr = ['c' => 'ali', 'a' => 'ali waked']; // Numeric keys
    // [$ali, $ali_waked] = $arr;
    // dd($ali_waked);
    $gateway = PaymentGatewayFactory::create($request->input('payment_method'));
    $response =  $gateway->create($order);
    // dd($response);
    return redirect()->away($response['payment_gateway_url']);    // return redirect()->away('https://google.com');
});

Route::prefix('payment')->controller(PaymentController::class)->group(function () {
    Route::post('/{paymentMethod:slug}/success', 'success')->name('payment.success');
    Route::get('/{paymentMethod:slug}/cancel', 'cancel')->name('payment.cancel');
});

Route::get('/countries', function (Request $request) {
    dd(Category::where('status', CategoryStatus::Active)->count());
    $order = Order::where('id', 14)->first();
    // dd(Currency::format($order->price, $order->currency));
    // dd(Product::query()->first()->get)
    $orders = Order::whereDate('created_at', '<', now()->subDays(6))
        // ->whereDoesntHave('ratings') // Ensure no ratings have been given
        ->get();
    $product = Product::where('id', 15)->first();
    $product->append('image')
        ->load(['category:id,name,slug', 'user:id,first_name,last_name', 'tags:name'])
        ->loadAvg('evaluations as rating', 'rating');
    // ->loadSum(['orderItems as total_sold' => function ($query) {
    //     $query->whereRelation('order', 'status', '=', OrderStatus::Pending);
    // }], 'quantity');
    // dd(Product::withAggregate('orders as count', 'quantity')->dd());

    dd($product->orders()->where('status', OrderStatus::Completed)->withSum('items', 'quantity')->first());
    dd($orders);
    dd(Product::whereRelation('orders', 'id', '=', $order->id)->whereDoesntHave('userReviews')->get());
    dd(Product::whereRelation('orders', 'id', '=', $order->id)->where(function ($builder) {
        $builder->whereHas('evaluations', function ($builder) {
            // return $builder->where('user_id', Auth::id());
        })->orWhereDoesntHave('evaluations');
    })
        ->dd());
});

Route::get('/servicess', [ServiceController::class, 'index']);

Route::get('/subscription/verify', [SubscriberController::class, 'verify'])->middleware('signed')->name('subscriber.verify');

Route::get('/test-cart/{type?}', function (Request $request, string $type) {
    $name = ['first' => 'ali'];
    dd(empty($name['first']));
    dd(User::query()->first()->billingAddress->city->name);
    // dd($type, $request->type);
    // dd(UserTypes::getAllTypes(UserTypes::Admin->value));
    dd(User::where('id', 1)->first()->cartItems()->first());
});
