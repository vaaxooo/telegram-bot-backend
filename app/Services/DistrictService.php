<?php

namespace App\Services;

use App\Models\District;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class DistrictService
{

	/**
	 * It returns a list of all districts in the database
	 * 
	 * @return An array with the code, status, and data.
	 */
	public function index()
	{
		$districts = District::with('city')->orderBy('id', 'desc')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $districts
		];
	}

	/**
	 * It returns a JSON response with a 200 status code and the district data
	 * 
	 * @param district The district object that was retrieved from the database.
	 * 
	 * @return 'code' => 200,
	 * 			'status' => 'success',
	 * 			'data' => 
	 */
	public function show($district)
	{
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $district
		];
	}


	/**
	 * It creates a new district and returns the created district
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with three keys: code, status, and data.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'city_id' => 'required|exists:cities,id',
			'name' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'data' => $validator->errors()
			];
		}
		$district = District::create([
			'city_id' => $request->city_id,
			'name' => $request->name,
			'slug' => $request->slug,
		]);
		return [
			'code' => 201,
			'status' => 'success',
			'data' => $district
		];
	}

	/**
	 * It updates a district and returns the updated district
	 * 
	 * @param request The request object.
	 * @param district The district object that was retrieved from the database.
	 * 
	 * @return An array with three keys: code, status, and data.
	 */
	public function update($request, $district)
	{
		$validator = Validator::make($request->all(), [
			'city_id' => 'required|exists:cities,id',
			'name' => 'required|string|max:255',
			'slug' => 'required|string|max:255',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'data' => $validator->errors()
			];
		}
		$district->city_id = $request->city_id;
		$district->name = $request->name;
		$district->slug = $request->slug;
		$district->save();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $district
		];
	}

	/**
	 * It deletes a district and returns the deleted district
	 * 
	 * @param district The district object that was retrieved from the database.
	 * 
	 * @return An array with three keys: code, status, and data.
	 */
	public function destroy($district)
	{
		District::where('id', $district->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $district
		];
	}

	/**
	 * It takes a district object, finds the district in the database, updates the district's visibility,
	 * and returns a response
	 * 
	 * @param district The district object that was passed to the method.
	 * 
	 * @return The district object is being returned.
	 */
	public function visibility($district)
	{
		$district = District::find($district->id);
		District::where('id', $district->id)->update(['is_active' => !$district->is_active]);
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $district
		];
	}
}
