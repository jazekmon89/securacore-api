<?php

namespace App\Http\Controllers\Api;

use Exception;
use Validator;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class APILoginController extends Controller
{

    /**
     * Log a User in, respond w/ a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required|min:8'
        ]);

        if ($validator->fails()) {
            $invalid = new ValidationException($validator);
            return response()->json([
                'success'   => false,
                'message'   => $invalid->getMessage(),
                'errors'   => $invalid->errors()
            ], 500);
        }

        $credentials = $request->only('email', 'password');
        $jwt_token = null;
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        
        if(!$user->status) {
            info('Account not activated.', ['email' => $credentials['email'], 'status' => $user->status]);
    
            return response()->json([
                'success' => false,
                'message' => 'Account has not been activated. Please request for activation code.',
            ], 401);
        }

        if (!$jwt_token = JWTAuth::attempt($credentials)) {

            info('User login attempt failed.', ['email' => $credentials['email']]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        info('User login attempt success.', ['user' => $user]);


        return response()->json([
            'success' => true,
            'message' => 'User logged-in successfully.',
            'token' => $jwt_token,
            'expires' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            'success' => true,
            'message' => 'Retreive logged-in user details successful.',
            'data' => auth()->user()
        ]);
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
