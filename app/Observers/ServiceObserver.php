<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Str;

class ServiceObserver
{
    /**
     * Undocumented function
     *
     * @param Service $service
     * @return void
     */
    public function updating(Service $service): void
    {
        if (!$service->isDirty('name')) {
            return;
        }
        $slug = Str::slug($service->name);
        $count = Service::whereLike('slug', "$slug%")->count();
        if ($count++) {
            $slug .= "-{$count}";
        }
        $service->slug = $slug;
    }
}
