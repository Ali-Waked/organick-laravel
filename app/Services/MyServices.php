<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Collection;

class MyServices
{
    public function getServices(?string $filter = null): Collection
    {
        return Service::filter(json_decode($filter))->get();
    }

    public function show(Service $service): Service
    {
        return $service;
    }

    public function update(Service $service, array $data): void
    {
        $old_icon = $service->icon;
        if (isset($data['image'])) {
            $data['icon'] = $service->uploadImage($data['image'], Service::FOLDER);
        }
        $service->update($data);
        if ($old_icon && isset($data['icon'])) {
            $service->removeImage($old_icon);
        }
    }
}
