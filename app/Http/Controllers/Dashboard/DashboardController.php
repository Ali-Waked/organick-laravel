<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\BlogStatus;
use App\Enums\CategoryStatus;
use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $type = $request->input('type', 'daily'); // daily, weekly, monthly, yearly
        return Response::json([
            'category' => [
                'active_count' => Category::where('is_active', true)->count(),
                'archived_count' => Category::where('is_active', false)->count(),
            ],
            'product' => [
                'active_count' => Product::where('is_active', true)->count(),
                'archived_count' => Product::where('is_active', false)->count(),
                'deleted_count' => Product::onlyTrashed()->count(),
            ],
            'news' => [
                'published_count' => News::where('is_published', true)->count(),
                'archived_count' => News::where('is_published', false)->count(),
            ],
            'order' => [
                'pinding_count' => Order::where('status', OrderStatus::Pending)->count(),
                'processing_count' => Order::where('status', OrderStatus::Processing)->count(),
                'shipped_count' => Order::where('status', OrderStatus::Shipped)->count(),
                'out_for_delivery_count' => Order::where('status', OrderStatus::OutForDelivery)->count(),
                'drivied_count' => Order::where('status', OrderStatus::Delivered)->count(),
                'completed_count' => Order::where('status', OrderStatus::Completed)->count(),
                'canceled_count' => Order::where('status', OrderStatus::Canceled)->count(),
                'refunded_count' => Order::where('status', OrderStatus::Refunded)->count(),
            ],
            'customer_count' => User::where('type', UserTypes::Customer)->count(),
            'moderator_count' => User::where('type', UserTypes::Moderator)->count(),
            'driver_count' => User::where('type', UserTypes::Driver)->count(),
            'subscriber_count' => Subscriber::query()->count(),
            'total_incoming' => DB::table('payments')
                ->where('paymentable_type', 'order')
                ->whereIn('paymentable_id', function ($query) {
                    $query->select('id')
                        ->from('orders')
                        ->where('status', OrderStatus::Completed);
                })
                ->sum('total_price'),

            'last_customers_registered' => User::where('type', UserTypes::Customer)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(['id', 'first_name', 'last_name', 'email', 'avatar', 'created_at']),

            'last_order_incoming' => Order::with('customer:id,email')->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'order_depended_city' =>
                Order::selectRaw('cities.name as city, COUNT(orders.id) as total')
                    ->join('addresses', function ($join) {
                        $join->on('orders.id', '=', 'addresses.addressable_id')
                            ->where('addresses.addressable_type', 'order');
                    })
                    ->join('cities', 'addresses.city_id', '=', 'cities.id')
                    ->groupBy('cities.name')
                    ->orderByDesc('total')
                    ->get(),
            'order_over_time' => DB::table('orders')
                ->selectRaw("
            COUNT(*) as total,
            CASE 
                WHEN ? = 'daily' THEN DATE(created_at)
                WHEN ? = 'weekly' THEN CONCAT(YEAR(created_at), '-W', WEEK(created_at))
                WHEN ? = 'monthly' THEN DATE_FORMAT(created_at, '%Y-%m')
                WHEN ? = 'yearly' THEN YEAR(created_at)
            END as period
        ", [$type, $type, $type, $type])
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get(),
            'order_by_status' => DB::table('orders')
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'last_publish_news' => News::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get(['id', 'title', 'subtitle', 'slug', 'cover_image']),
            'income_by_payment_method' => \App\Models\PaymentMethod::withSum([
                'payments as total_income' => function ($query) {
                    $query->whereHasMorph('paymentable', [\App\Models\Order::class], function ($q) {
                        $q->where('status', OrderStatus::Completed);
                    });
                }
            ], 'total_price')->get(['id', 'name'])

        ]);
    }
}
