<?php

namespace App\Services;

use App\Enums\AbilityStatus;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class AbilityService
{
    public function all(): JsonResponse
    {
        $abilities = App::make('abilities');
        return response()->json([
            'abilities' => $abilities,
            'status' => AbilityStatus::cases(),
        ]);
    }
    public function create(Role $role, array $data): Collection
    {
        return $role->abilities()->createMany($data);
    }
    public function update(Role $role, array $data)
    {
        foreach ($data as $ability) {
            $role->abilities()->where([
                'ability' => $ability['ability']
            ])->update(['status' => $ability['status']]);
        }
    }
}
