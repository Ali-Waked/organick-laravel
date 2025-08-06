<?php

namespace App\Http\Controllers;

use App\Enums\UserTypes;
use App\Events\NewCustomerRegisteredEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteController extends Controller
{
    public function redirect(string $driver): RedirectResponse
    {
        return Socialite::driver($driver)->redirect();
        // scopes(['https://www.googleapis.com/auth/userinfo.profile'])
    }
    public function callback(string $driver): RedirectResponse
    {
        $user = Socialite::driver($driver)->user();

        $fullName = trim($user->getName());
        [$first_name, $last_name] = array_pad(explode(' ', $fullName, 2), 2, '');
        // Log::info("gender {$user->gender}");
        // dd($user);
        $authUser = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'password' => Hash::make(Str::password()),
            'first_name' => $first_name,
            'last_name' => $last_name ?? '',
            'type' => UserTypes::Customer->value,
            'avatar' => $user->avatar,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'email_verified_at' => now(),
            'divider' => $driver,
        ]);

        Auth::login($authUser);
        if ($authUser->wasRecentlyCreated) {
        }
        event(new NewCustomerRegisteredEvent($authUser));
        return Redirect::away(path: Config::get('app.front-url'));
    }
}
