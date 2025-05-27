<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\services\UserLogin;
use App\services\UserRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected UserRegister $userRegister;
    protected UserLogin $userLogin;
    public function __construct(UserRegister $userRegister){
        $this->userRegister = $userRegister;
        $this->userLogin = new UserLogin();
    }

    public function register(Request $request):JsonResponse
    {
        return $this->userRegister->register($request);
    }

    public function login(Request $request):JsonResponse{
      return  $this->userLogin->login($request);
    }

    public function profile(Request $request):JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }
}

