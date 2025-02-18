<?php

namespace App\Services;

use App\Enums\UserTypes;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function getUsers(UserTypes $userType, ?string $filter = null): LengthAwarePaginator
    {
        return User::filter($userType, json_decode($filter))->paginate();
    }

    public function store(UserTypes $userType, array $data): array
    {
        $password = Str::random(12);
        $userInfo = array_merge([
            'password' => Hash::make($password),
            'avatar' => User::__callStatic('uploadImage', [$data['image'], User::FOLDER]),
            'type' => $userType
        ], $data);
        $user =  User::create($userInfo);
        // if (isset($data['socials'])) {
        //     $socials = [];
        //     foreach ($data['socials'] as $key => $value) {
        //         $socials[] = [
        //             'title' => $key,
        //             'link' => $value
        //         ];
        //     }
        //     $user->socials()->createMany($socials);
        // }
        if (!empty($data['phone_number'])) {
            $user->billingAddress()->create(['phone_number' => $data['phone_number']]);
        }
        return [$user, $password];
    }

    public function show(User $user)
    {
        return $user->load(['billingAddress', 'billingAddress.city'])->loadCount(['orders']);
    }

    public function update(User $user, array $data): void
    {
        $old_image = $user->avatar;

        if (isset($data['image'])) {
            $data['avatar'] = $user->uploadImage($data['image'], User::FOLDER);
        }

        $user->update($data);

        // if (isset($data['socials'])) {
        //     foreach ($data['socials'] as $key => $value) {
        //         $user->socials()->update([$key => $value]);
        //     }
        // }
        if (!empty($data['phone_number'])) {
            $user->billingAddress()->update(['phone_number' => $data['phone_number']]);
        }

        if ($old_image && isset($data['avatar']) && !empty($data['avatar'])) {
            $user->removeImage($old_image);
        }
    }

    public function delete(User $user): void
    {
        $user->delete();
        $user->removeImage($user->avatar);
    }
}
