<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryPassword;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Verification;

class AuthService
{

	/**
	 * It checks if the user is authenticated, and if not, it returns a JSON response with a 401 status
	 * code
	 */
	public function login()
	{
		$credentials = request(['email', 'password']);
		if (!$token = auth()->attempt($credentials)) {
			return response()->json([
				'code' => 401,
				'message' => 'E-mail or password is wrong',
				'status' => 'error'
			]);
		}
		return $this->respondWithToken($token);
	}

	/**
	 * It returns the authenticated user
	 * 
	 * @return The user object
	 */
	public function me()
	{
		$user = User::where('email', auth()->user()->email)->first();
		return [
			'code' => 200,
			'status' => 'success',
			'user' => $user
		];
	}

	/**
	 * It logs the user out
	 * 
	 * @return An array with a code, status, and message.
	 */
	public function logout()
	{
		auth()->logout();

		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Successfully logged out'
		];
	}

	/**
	 * It takes the token from the request, and then returns a new token
	 * 
	 * @return The token is being returned.
	 */
	public function refresh()
	{
		return $this->respondWithToken(auth()->refresh());
	}

	/**
	 * It returns an array with the access token, token type, and expiration time
	 * 
	 * @param token The JWT token that will be used for authentication.
	 * 
	 * @return 'access_token' => ,
	 * 				'token_type' => 'bearer',
	 * 				'expires_in' => auth()->factory()->getTTL() * 60
	 */
	public function respondWithToken($token)
	{
		$user = auth()->user();
		$user->token = $token;
		return [
			'code' => 200,
			'status' => 'success',
			'data' => [
				'user' => $user
			]
		];
	}
}
