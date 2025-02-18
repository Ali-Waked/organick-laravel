<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AbilityStatus;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\AbilityService;
use App\Services\RoleService;

class RoleController extends Controller
{

    public function __construct(
        protected RoleService $roleService,
        protected AbilityService $abilityService
    ) {
        //
    }

    /**
     * Get All Role
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function index(Request $request): Collection
    {
        return $this->roleService->getAllRole($request->filter);
    }

    /**
     * Create New Role
     * @param \App\Http\Requests\RoleRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = $this->roleService->create($request->safe()->only('name')['name']);
            $RoleAbilities = $this->abilityService->create($role, $request->safe()->only('abilities')['abilities']);
            DB::commit();
            return $RoleAbilities;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get Role
     * @param \App\Models\Role $role
     * @return \App\Models\Role
     */
    public function show(Role $role): Role
    {
        return $this->roleService->show($role);
    }

    /**
     * Update Role
     * @param \App\Http\Requests\RoleRequest $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->roleService->update($role, $request->safe()->only('name')['name']);
            $this->abilityService->update($role, $request->safe()->only('abilities')['abilities']);
            DB::commit();
            return Response::json([
                'message' => 'updated sucessfully',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Remove Role
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->delete($role);
        return Response::json([
            'message' => 'delete successflly',
        ]);
    }
}
