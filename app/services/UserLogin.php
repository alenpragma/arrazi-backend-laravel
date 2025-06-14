<?php

namespace App\services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class UserLogin
{
    public function login($request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid password'
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login success',
            'token' => $token,
            'user' => $user, // optional: user info if needed
        ]);
    }
}
