<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccessTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AccessTokenController extends Controller
{
    public function store(AccessTokenRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->ip())->plainTextToken;
            // $cookie = cookie('token', $token, 60, null, null, true, true);
            // $cookie = cookie('token', $token, 60, null, null, false, true);
            Auth::login($user);
            return Response::json([
                'token' => $token,
                'user' => $user,
            ]);
            // ->cookie(
            //     'token', // Cookie name
            //     $token, // Token value
            //     60 * 24 * 7, // Expiration time (in minutes)
            //     null, // Path
            //     null, // Domain
            //     false, // Secure
            //     true, // HttpOnly
            //     false, // Raw
            //     'Strict' // SameSite
            // );
        }
        return Response::json([
            'message' => 'place enter correct email and password'
        ]);
    }
}
