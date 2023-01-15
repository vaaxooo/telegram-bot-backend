<?php

namespace App\Services;

use App\Models\User;

use Illuminate\Support\Facades\Validator;

class WorkerService
{

	/**
	 * It returns a list of all the relations between cities and categories
	 * 
	 * @return The index method is returning an array with the code, status, and data.
	 */
	public function index()
	{
		$workers = User::where('role', 'manager')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $workers
		];
	}


	/**
	 * It returns a relation between a city and a category
	 * 
	 * @param id The id of the relation.
	 * 
	 * @return The show method is returning an array with the code, status, and data.
	 */
	public function show($worker)
	{
		$worker = User::find($worker);
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $worker
		];
	}

	/**
	 * It takes an array of categories and a city id, and creates a relation between the city and the
	 * categories
	 * 
	 * @param request The request object.
	 * 
	 * @return An array of arrays.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|unique:users,name',
			'password' => 'required|string',
			'referral' => 'required|string'
		]);

		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'errors' => $validator->errors()
			];
		}
		$user = User::create([
			'name' => $request->name,
			'password' => bcrypt($request->password),
			'referral' => $request->referral
		]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Worker created successfully',
			'data' => $user
		];
	}

	/**
	 * It takes an array of categories and a city id, and updates the relations between the city and the
	 * categories
	 * 
	 * @param request The request object.
	 * 
	 * @return An array of arrays.
	 */
	public function update($request, $user)
	{
		$user = User::find($user->id);
		if ($request->password) {
			$user->password = bcrypt($request->password);
		}
		$user->name = $request->name;
		$user->referral = $request->referral;
		$user->save();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Worker updated successfully',
			'data' => $user
		];
	}

	/**
	 * It deletes a relation between a city and a category
	 * 
	 * @param id The id of the relation.
	 * 
	 * @return The destroy method is returning an array with the code, status, and message.
	 */
	public function destroy($user)
	{
		User::where('id', $user)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Worker deleted successfully'
		];
	}
}
