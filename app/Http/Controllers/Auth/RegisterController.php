<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', "unique:users"],
        ]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $otpCode = mt_rand(10000, 99999);

        $user = User::create([
            'email' => $request->email,
            'register_token' => $otpCode
        ]);

        $user->notify(new \App\Notifications\SendOtpEmail($otpCode));

        return response()->json(['success' => true, 'message' => "user registered."]);

    }

    public function completeRegistration(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'family' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'integer'],
            'password' => [
                'required',
                'string',
                'min:8'
            ],

        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $user = User::where("email", $request->email)->where("register_token", $request->otp)->first();

        if ($user) {

            $user->name = $request->name;
            $user->family = $request->family;
            $user->password = Hash::make($request->password);
            $user->register_token = null;
            $user->email_verified_at = Carbon::now();
            if ($user->save())
                return response()->json(['success' => true, 'message' => "user successfully registered."]);
            else
                return response()->json(['success' => false, 'message' => "please try again"]);

        } else
            return response()->json(['success' => false, 'message' => "User not found."]);


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
