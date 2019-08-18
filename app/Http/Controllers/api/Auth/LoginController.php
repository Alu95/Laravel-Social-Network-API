<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as TymonJWTAuth;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request as IlluminateRequest;

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
    protected $redirectTo = '';
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TymonJWTAuth $auth)
    {
        //$this->middleware('guest')->except('logout');
        $this->auth = $auth;
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(IlluminateRequest $request)
    {
        $this->incrementLoginAttempts($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            return response()->json([
                'success' => false,
                'errors' => [
                    "Locked out"
                ]
            ]);
        }
        try {
            if (!$token = $this->auth->attempt($request->only('email','password'))){
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => [
                            "Invalid email address or password"
                        ]
                    ]
                ], 422);
            }
        }
        catch(JWTException $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => [
                        "Invalid email address or password"
                    ]
                ]
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $request->user(),
            //'scope' => $request->user()->scope(),
            'token' => $token
        ], 200);
    }

}
