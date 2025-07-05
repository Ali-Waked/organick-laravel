<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\UserTypes;
use App\Events\AddNewMember;
use App\Events\AddNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function __construct(protected UserService $userService)
    {
        //
    }

    /**
     * Get Users
     * @param \Illuminate\Http\Request $request
     * @param \App\Enums\UserTypes $userType
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request, UserTypes $userType): LengthAwarePaginator
    {
        return $this->userService->getUsers($userType, $request->filter);
    }

    /**
     * Add New User (Driver | Moderator)
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\Enums\UserTypes $userType
     * @return \App\Models\User
     */
    public function store(UserRequest $request, UserTypes $userType): User
    {
        try {
            DB::beginTransaction();
            [$user, $password] = $this->userService->store($userType, $request->validated());
            DB::commit();
            AddNewUser::dispatch($user, $password);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return $user;
    }

    /**
     * Get User
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public function show(UserTypes $userType, User $user): User
    {
        return $this->userService->show($user);
    }

    /**
     * Update User Information
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, UserTypes $userType, User $user): JsonResponse
    {
        $this->userService->update($user, $request->validated());
        return response()->json(['message' => 'User updated successfully'], 200);
    }

    /**
     * Delete User
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserTypes $userType, User $user): JsonResponse
    {
        $this->userService->delete($user);

        return Response::json([
            'message' => "Delete {$user->full_name} Successflly",
        ]);
    }

    public function fetchAllDrivers()
    {
        return User::where('type', UserTypes::Driver->value)->get();
    }
}
