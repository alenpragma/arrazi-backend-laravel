<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'status' => true,
            'data' => $user->loadNetwork(),
        ]);
    }
}
