<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\BlogStatus;
use App\Enums\CategoryStatus;
use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return Response::json([
            'category_count' => Category::where('status', CategoryStatus::Active)->count(),
            'product_count' => Product::where('status', ProductStatus::Active)->count(),
            'blog_count' => Blog::where('status', BlogStatus::Published)->count(),
            'order_count' => Order::where('status', OrderStatus::Completed)->count(),
            'user_count' => User::where('user_id', UserTypes::Customer)->count(),
            'subscriber_count' => Subscriber::query()->count(),

        ]);
    }
}
