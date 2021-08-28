<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validateData['password'] = Hash::make($request->password);

        $user = User::create($validateData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(
            [
                'user' => $user,
                'access_token' => $accessToken,
            ],
            201
        );
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($loginData)) {
            return response()->json([
                'message'  => 'Invalid credentials',
            ], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            // 'user' => auth()->user(),
            'access_token' => $accessToken,
            'refresh_token' => $accessToken
        ], 200);
    }

    public function profile()
    {
        return response()->json([
            auth()->user(),
        ], 200);
    }


    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function refreshToken()
    {
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['access_token' => $accessToken], 200);
    }
}
