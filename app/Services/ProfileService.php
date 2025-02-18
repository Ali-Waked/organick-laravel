<?php

namespace App\Services;

use App\Http\Resources\ProfileResource;
use App\Models\User;
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
            $userInfo['avatar'] = $user->uploadImage($userInfo['image'], User::FOLDER);
        }
        $user->update($userInfo);
        $user->billingAddress()->update($address);
    }
}
