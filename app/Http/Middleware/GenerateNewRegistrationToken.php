<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class GenerateNewRegistrationToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_is_uncomplete_registration = User::where("email", $request->email)->where("email_verified_at", null)->first();
        if ($user_is_uncomplete_registration) {

            $date = Carbon::parse($user_is_uncomplete_registration->registration_token_expire_time);
            $now = Carbon::now();

            $diff = $date->diffInMinutes($now);

            if ($diff >= 15) {
                $otpCode = mt_rand(10000, 99999);

                $user_is_uncomplete_registration->register_token = $otpCode;
                $user_is_uncomplete_registration->registration_token_expire_time = Carbon::now();
                $user_is_uncomplete_registration->save();

                $user_is_uncomplete_registration->notify(new \App\Notifications\SendOtpEmail($otpCode));

                return response()->json(['success' => true, 'message' => "new registration token has been snt."]);
            } else {
                $incresed_time = 15 - $diff;

                return response()->json(['success' => false, 'message' => "please check email and use otp code Or try again after {$incresed_time} minutes."]);
            }


        } else {
            return $next($request);
        }
    }
}
