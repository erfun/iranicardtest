<?php

namespace App\Http\Middleware;

use App\Models\User;
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
            $otpCode = mt_rand(10000, 99999);

            $user_is_uncomplete_registration->register_token = $otpCode;
            $user_is_uncomplete_registration->save();

            $user_is_uncomplete_registration->notify(new \App\Notifications\SendOtpEmail($otpCode));


            return response()->json(['success' => true, 'message' => "new registration token has been snt."]);
        } else {
            return $next($request);
        }
    }
}
