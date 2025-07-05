<?php

namespace App\Services;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function get(): ProfileResource
    {
        return new ProfileResource(Auth::user());
    }

    public function update(array $userInfo, array $address): void
    {
        $user = Auth::user();
        if (!empty($userInfo['avatar'])) {
            $userInfo['avatar'] = $user->uploadImage($userInfo['avatar'], User::FOLDER);
        }
        $user->update($userInfo);
        info($address['billing_address']);
        $reslut = Address::updateOrCreate(['addressable_id' => $user->id, 'addressable_type' => 'user'], $address['billing_address']);
        info('reslut ' . $reslut);
        info($user->billingAddress);
    }
}
