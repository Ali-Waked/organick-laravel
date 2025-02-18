<?php

namespace App\Observers;

use App\Enums\BlogStatus;
use App\Events\BlogPublished;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogObserver
{
    /**
     * Handle the Blog "created" event.
     */
    public function creating(Blog $blog): void
    {
        $user = Auth::guard('sanctum')->user();
        $blog->user_id = $user->id;
        $blog->auther = "{$user->first_name} {$user->last_name}";
    }

    public function saving(Blog $blog): void
    {
        if ($blog->isDirty('status') && $blog->status == BlogStatus::Published) {
            BlogPublished::dispatch($blog);
        }
    }

    /**
     * Handle the Blog "updated" event.
     */
    public function updated(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "deleted" event.
     */
    public function deleted(Blog $blog): void
    {
        // $blog->removeImage();
    }

    /**
     * Handle the Blog "restored" event.
     */
    public function restored(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "force deleted" event.
     */
    public function forceDeleted(Blog $blog): void
    {
        //
    }
}
