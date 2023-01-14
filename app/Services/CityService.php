<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Validator;

class CityService
{

	/**
	 * > It returns a list of all cities in the database
	 * 
	 * @return An array with a code, status, and data.
	 */
	public function index()
	{
		$cities = City::get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $cities
		];
	}

	/**
	 * It creates a new city and returns the city object
	 * 
	 * @param request The request object
	 * 
	 * @return An array with three keys: code, status, and data.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'data' => $validator->errors()
			];
		}
		$city = City::create($request->all());
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $city
		];
	}

	/**
	 * It returns a JSON response with a 200 status code and the data of the city
	 * 
	 * @param city The name of the city to show.
	 * 
	 * @return An array with three keys: code, status, and data.
	 */
	public function show($city)
	{
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $city
		];
	}

	/**
	 * It takes a request and a city, validates the request, updates the city, and returns a response
	 * 
	 * @param request The request object
	 * @param city The city object that we're updating.
	 * 
	 * @return An array with the code, status, and data.
	 */
	public function update($request, $city)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'data' => $validator->errors()
			];
		}
		$city->update($request->all());
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $city
		];
	}

	/**
	 * It deletes the city from the database.
	 * 
	 * @param city The city object that was passed in from the route.
	 * 
	 * @return The city object is being returned.
	 */
	public function destroy($city)
	{
		City::where('id', $city->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $city
		];
	}
}
