<?php

namespace App\services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserRegister
{
    public function register($request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:150',
                'email' => 'required|unique:users,email|string|max:255',
                'position' => 'required|in:left,right',
                'refer_code' => 'required|string|max:50',
                'password' => 'required|string|min:6',
            ]);

            $referUser = User::where('refer_code', $request->refer_code)->first();

            if (!$referUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid referral code',
                ]);
            }

            $position = $request->position;
            $finalUpline = null;

            // 1st Check if position is directly available under refer user
            if ($position === 'left' && $referUser->left_user_id === null) {
                $finalUpline = $referUser;
            } elseif ($position === 'right' && $referUser->right_user_id === null) {
                $finalUpline = $referUser;
            } else {
                // Otherwise traverse down that side
                $finalUpline = ($position === 'left')
                    ? $this->findEmptyPositionLeft($referUser)
                    : $this->findEmptyPositionRight($referUser);

                if (!$finalUpline) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No empty position found',
                    ]);
                }
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'position' => $position,
                'refer_by' => $referUser->id,
                'upline_id' => $finalUpline->id,
                'refer_code' => strtoupper(uniqid()),
            ]);

            if ($position === 'left') {
                $finalUpline->left_user_id = $user->id;
            } else {
                $finalUpline->right_user_id = $user->id;
            }

            $finalUpline->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'data' => [
                    'token' => $token,
                    'user' => $user,
                ],
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function findEmptyPositionLeft($user)
    {
        $left = User::find($user->left_user_id);
        if (!$left) return null;

        if ($left->left_user_id === null) return $left;

        return $this->findEmptyPositionLeft($left);
    }

    private function findEmptyPositionRight($user)
    {
        $right = User::find($user->right_user_id);
        if (!$right) return null;

        if ($right->right_user_id === null) return $right;

        return $this->findEmptyPositionRight($right);
    }
}
