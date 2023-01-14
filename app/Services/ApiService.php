<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ApiService
{


	/* ########################## CLIENT INFO ########################## */

	/**
	 * It creates a new client if it doesn't exist, and returns an error if the request is invalid
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with the following keys:
	 */
	public function start($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
			'language_code' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}
		$client = Client::where('telegram_id', $request->telegram_id)->first();
		if (!$client) {
			$client = Client::create($request->all());
			return [
				'code' => 201,
				'status' => 'success',
				'message' => 'Client created',
				'data' => $client
			];
		}
		if ($client->is_banned == true) {
			return [
				'code' => 403,
				'status' => 'error',
				'message' => 'Client is banned',
				'data' => $client
			];
		}
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Client exists',
			'data' => $client
		];
	}

	/**
	 * It returns the user's profile.
	 * 
	 * @param request The request object.
	 */
	public function getProfile($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}
		$client = Client::select('telegram_id', 'nickname', 'first_name', 'last_name', 'balance', 'referral')->where('telegram_id', $request->telegram_id)->first();
		if (!$client) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Client not found',
				'data' => $client
			];
		}
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $client
		];
	}

	/**
	 * It gets the balance of a client by his telegram id
	 * 
	 * @param request The request object.
	 */
	public function getBalance($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}
		$client = Client::select('balance')->where('telegram_id', $request->telegram_id)->first();
		if (!$client) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Client not found',
				'data' => $client
			];
		}
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $client
		];
	}

	/**
	 * It sets the referral of a client
	 * 
	 * @param request The request object.
	 * 
	 * @return The return is an array with the following keys:
	 */
	public function setReferral($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
			'referral' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}
		$client = Client::where('telegram_id', $request->telegram_id)->first();
		if (!$client) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Client not found',
				'data' => $client
			];
		}

		if (User::where('referral', $request->referral)->exists() == false) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Referral not found',
			];
		}

		$client->referral = $request->referral;
		$client->save();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Referral set',
			'data' => $client
		];
	}

	/* ########################## CITIES ########################## */
}
