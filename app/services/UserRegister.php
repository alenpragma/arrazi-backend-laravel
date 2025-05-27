<?php

namespace App\services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegister
{
    public function register($request){
        try{
            $request->validate([
                'name' => 'required|string|max:150',
                'email' => 'required|unique:users,email|string|max:255',
                'position' => 'required|string|max:60',
                'refer_code' => 'nullable|string|max:50',
                'password' => 'required|string|min:6',
            ]);

            $referUser  = User::where('refer_code', $request->input('refer_code'))->first();

            if(!$referUser){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Referral Code'
                ]);
            }

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'position' => $request->input('position'),
                'refer_by' => $referUser->id,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => true,
                'data' => [
                    'token' => $token,
                    'user' => $user,
                ]
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
