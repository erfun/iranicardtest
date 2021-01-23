<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = User::where("email", $request->get('email'))->first();


        if ($user) {

            $otpCode = mt_rand(10000, 99999);

            PasswordReset::create([
                "email" => $user->email,
                "token" => $otpCode,
                "created_at" => Carbon::now(),
            ]);

            $user->notify(new \App\Notifications\SendResetPasswordOtp($otpCode));

            return response()->json(['success' => true, 'message' => "your password reset key sent,please check email."]);

        } else
            return response()->json(['success' => false, 'message' => "user not found."]);
    }

    public function completeResetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'token' => ['required', 'integer'],
            'password' => [
                'required',
                'string',
                'min:8'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }


        $get_password_token = PasswordReset::where("email", $request->email)->where("token", $request->token)->where("created_at", ">", Carbon::now()->subMinutes(15))->first();

        if ($get_password_token) {

            $user = User::where("email", $request->get('email'))->first();

            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();


                PasswordReset::where("email", $request->email)->delete();


                return response()->json(['success' => true, 'message' => "password has been changed successfully."]);
            } else
                return response()->json(['success' => false, 'message' => "something wrong please contact with administrator."]);

        } else
            return response()->json(['success' => false, 'message' => "invalid token,or token is expired."]);

    }
}
