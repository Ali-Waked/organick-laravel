<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationToRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($notify_id = $request->query('notify_id')) {
            $user = $request->user();
            $notifications = $user->notifications;
            $notifications->where('id', $notify_id)->markAsRead();
        }
        return $next($request);
    }
}
