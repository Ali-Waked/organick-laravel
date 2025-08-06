<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController
{
    public function __invoke(Request $request): JsonResponse
    {
        $filters = json_decode($request->query('filter'));
        $notifications = $this->getNotificationsByStatus($filters, $request->query('paginate', 5));
        return Response::json([
            'notifications' => $notifications,
            'unread_count' => $notifications->whereNull('read_at')->count(),
            'total_unread_count' => Auth::guard('sanctum')->user()->unreadNotifications()->count(),
            'read_count' => $notifications->whereNotNull('read_at')->count(),
            'total_count' => $notifications->count(),
            'status' => $filters?->status ?? null,
            'message' => 'Notifications fetched successfully',
        ]);
    }
    /**
     * Get notifications based on the specified status.
     *
     * @param string|null $status
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getNotificationsByStatus(?object $filter, int $paginate = 5): LengthAwarePaginator
    {
        $sortingOrder = strtolower($filter->sortingOrder ?? 'desc');
        $sortingOrder = in_array($sortingOrder, ['asc', 'desc']) ? $sortingOrder : 'desc';

        $query = DatabaseNotification::query()
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', 'user')
            ->with('notifiable')
            ->orderBy('created_at', $sortingOrder);
        if (!empty($filter?->status)) {
            $this->applyStatusFilter($query, strtolower($filter->status));
        }
        return $query->paginate($paginate);
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
