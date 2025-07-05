<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileService $profileService)
    {
        //
    }

    /**
     * Get Porfile
     * @return \App\Http\Resources\ProfileResource
     */
    public function index(): ProfileResource
    {
        return $this->profileService->get();
    }

    /**
     * Update Profile
     * @param \App\Http\Requests\ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->profileService->update($request->only(['first_name', 'last_name', 'email', 'avatar', 'bithday', 'gender']), $request->only(['billing_address.street', 'billing_address.phone_number', 'billing_address.city_id', 'billing_address.notes']));
            DB::commit();
            return Response::json([
                'message' => 'update profile successflly',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
