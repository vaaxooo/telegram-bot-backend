<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ClientService
{

	/**
	 * It gets all the clients from the database and returns them in a JSON format
	 * 
	 * @return An array with the code, status, and clients.
	 */
	public function index()
	{
		$clients = Client::orderBy('id', 'desc')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $clients
		];
	}

	/**
	 * > This function returns a JSON response with the client's data
	 * 
	 * @param Client client The model instance that the controller action is going to receive.
	 * 
	 * @return An array with the code, status, and client.
	 */
	public function show(Client $client)
	{
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $client
		];
	}

	/**
	 * It creates a new client in the database
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with the following keys:
	 */
	public function store($request)
	{
		Client::create([
			'telegram_id' => $request->telegram_id,
			'nickname' => $request->nickname,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'language_code' => $request->language_code,
			'phone_number' => $request->phone_number,
		]);

		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Client created successfully'
		];
	}

	/**
	 * It takes a request and a client id, validates the request, and updates the client's balance
	 * 
	 * @param request The request object
	 * @param client The client ID
	 * 
	 * @return An array with the following keys:
	 */
	public function setBalance($request, $client)
	{
		$validator = Validator::make($request->all(), [
			'balance' => 'required|numeric'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => $validator->errors()
			];
		}
		Client::where('id', $client)->update(['balance' => $request->balance]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Client balance updated successfully'
		];
	}

	/**
	 * It deletes the client from the database
	 * 
	 * @param client The client object that was passed to the controller.
	 * 
	 * @return An array with a code, status, and message.
	 */
	public function destroy($client)
	{
		Client::where('id', $client->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Client deleted successfully'
		];
	}

	/**
	 * It takes a client object, sets the is_banned property to true, and saves the client
	 * 
	 * @param client The client object that you want to ban.
	 * 
	 * @return An array with a code, status, and message.
	 */
	public function banned($client)
	{
		Client::where('id', $client->id)->update(['is_banned' => !$client->is_banned]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Client status banned updated successfully'
		];
	}
}
