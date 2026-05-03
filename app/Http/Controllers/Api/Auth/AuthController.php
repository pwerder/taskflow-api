<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages('Invalid access!');
        }

        $user = Auth::user();

        $token = $user->createToken('auth_task_flow')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ], Response::HTTP_OK);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_task_flow')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ], Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
       $request->user()->currentAccessToken()->delete();

       return response()->noContent();
    }
}
