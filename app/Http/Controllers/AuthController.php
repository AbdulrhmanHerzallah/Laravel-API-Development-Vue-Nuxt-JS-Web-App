<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\User as UserResource;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login' , 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function register(UserRegisterRequest $request)
    {
        User::create($request->all());
        return $this->login($request);
    }


    public function login(UserLoginRequest $request) {
        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'errors' => [
                    'email' => ['Sorry we cant find you with those details.'],
                ],
            ], 422);
        };

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token,
            ],
        ]);
    }

/*
    public function rememberMy(Request $request){

        if (response()->json(['rememberMe' => $request->rememberMe = true] , 200)){
                echo "good";

//                $token = auth()->setTTL(5)->refresh();
          $token = $this->respondWithToken(auth()->setTTL(5)->refresh());
                dd($token);
            }
    }
 */
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function user(Request $request) {
        return new UserResource($request->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
