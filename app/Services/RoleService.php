<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Collection;

class RoleService
{
    public function getAllRole(?string $search = null): Collection
    {
        return Role::filter($search)->get();
    }

    public function create($name): Role
    {
        return  Role::create([
            'name' => $name,
        ]);
    }
    public function update(Role $role, ?string $name = null): ?bool
    {
        if ($name) {
            return  $role->update([
                'name' => $name,
            ]);
        }
    }
    public function show(Role $role): Role
    {
        return $role->load(['abilities', 'users:id,first_name,last_name,email']);
    }


    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
