<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginWithApi(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'email' => ['required', 'string', 'email'],
            'password' => [
                'required',
                'string',

            ],

        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $userData = array(
            'email' => $request->get('email'),
            'password' => $request->get('password')
        );

        if (Auth::attempt($userData)) {


            $user = User::where("email", $request->get('email'))->first();

            $login_token = Str::random(60);

            $user->api_token =  $login_token;

            if ($user->save())
                return response()->json(['success' => true, 'token' => $login_token]);
            else
                return response()->json(['success' => false, 'message' => "please try again"]);
        } else
            return response()->json(['success' => false, 'message' => "invalid username or password."]);
    }
}
