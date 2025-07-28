<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\services\UserLogin;
use App\services\UserRegister;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            'left_pv' => $user->getTotalLeftPoints() - $user->left_points,
            'right_pv' => $user->getTotalRightPoints() - $user->right_points,
            'binary_earnings' => DB::table('matching_bonus_logs')->where('user_id', $user->id)->sum('amount'),
            'total_match' => DB::table('matching_bonus_logs')->where('user_id', $user->id)->count(),
            'direct_refer' => User::where('refer_by', $user->id)->count(),
        ]);
    }

    public function ForgotPasswordSendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input("email");
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ], 404);
        }


        $code = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $code,
                'created_at' => Carbon::now()
            ]
        );

        // Send Email
        try {
            Mail::send('mail.Forgotpassword', ['user' => $user, 'code' => $code], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Your Password Reset Code');
            });
        }catch (\Exception $exception){
            return response()->json([
                "status" => false,
                "message" => $exception->getMessage()
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Verification code sent to email"
        ]);
    }

    public function ResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'password' => 'required|min:6'
        ]);

        $email = $request->email;
        $code = $request->code;

        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $code)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid code'
            ], 400);
        }

        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'Code expired'
            ], 400);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Optionally remove reset token
        DB::table('password_resets')->where('email', $email)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully'
        ]);
    }
}

