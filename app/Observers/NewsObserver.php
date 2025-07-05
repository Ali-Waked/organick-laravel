<?php

namespace App\Observers;

use App\Enums\NewsStatus;
use App\Events\NewsPublished;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     */
    public function creating(News $news): void
    {
        $user = Auth::guard('sanctum')->user();
        $news->created_by = $user->id;
        // $news->auther = "{$user->first_name} {$user->last_name}";
    }
    /**
     * Handle the News "created" event.
     */
    public function saving(News $news): void
    {
        if ($news->isDirty('is_published') && $news->is_published) {
            NewsPublished::dispatch($news);
        }
        $slug = Str::slug($news->title);
        $count = News::whereLike('slug', "{$slug}%")->count();
        if ($count > 0) {
            ++$count;
            $slug = "{$slug}-{$count}";
        }
        $news->slug = $slug;
    }

    /**
     * Handle the Blog "updated" event.
     */
    public function updated(News $news): void
    {
        //
    }

    /**
     * Handle the Blog "deleted" event.
     */
    public function deleted(News $news): void
    {
        // $blog->removeImage();
    }

    /**
     * Handle the Blog "restored" event.
     */
    public function restored(News $news): void
    {
        //
    }

    /**
     * Handle the News "force deleted" event.
     */
    public function forceDeleted(News $news): void
    {
        //
    }
}
