<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $notifications = $this->getNotificationsByStatus($request->query('status'));
        return Response::json([
            'notifications' => $notifications,
        ]);
    }
    /**
     * Get notifications based on the specified status.
     *
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getNotificationsByStatus(?string $status): Collection
    {
        return Auth::guard('sanctum')->user()->notifications()
            ->when($status, function ($query, string $value): void {
                $this->applyStatusFilter($query, $value);
            })
            ->with('notifiable')
            ->get();
    }

    /**
     * Apply the status filter to the query.
     *
     * @param $query
     * @param string $status
     */
    private function applyStatusFilter($query, string $status): void
    {
        if ($status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($status === 'unread') {
            $query->whereNull('read_at');
        }
    }
}
