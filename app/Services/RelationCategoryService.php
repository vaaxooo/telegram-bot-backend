<?php

namespace App\Services;

use App\Models\RelationCategory;
use App\Models\City;
use App\Models\Category;

use Illuminate\Support\Facades\Validator;

class RelationCategoryService
{

	/**
	 * It returns a list of all the relations between cities and categories
	 * 
	 * @return The index method is returning an array with the code, status, and data.
	 */
	public function index()
	{
		$relations = RelationCategory::with('city', 'category')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $relations
		];
	}


	/**
	 * It returns a relation between a city and a category
	 * 
	 * @param id The id of the relation.
	 * 
	 * @return The show method is returning an array with the code, status, and data.
	 */
	public function show($id)
	{
		$relation = RelationCategory::with('city', 'category')->find($id);
		if (!$relation) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Relation not found'
			];
		}
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $relation
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
			'city_id' => 'required',
			'categories' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}

		$temp_array = [];
		foreach ($request->categories as $category) {
			$relation = RelationCategory::where('city_id', $request->city_id)->where('category_id', $category)->first();
			if (!$relation) {
				array_push($temp_array, [
					'city_id' => $request->city_id,
					'category_id' => $category
				]);
			}
		}
		RelationCategory::insert($temp_array);
		return [
			'code' => 201,
			'status' => 'success',
			'message' => 'Relations created'
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
	public function update($request)
	{
		$validator = Validator::make($request->all(), [
			'city_id' => 'required',
			'categories' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}

		$relations = RelationCategory::where('city_id', $request->city_id)->get();
		foreach ($relations as $relation) {
			$relation->delete();
		}

		$temp_array = [];
		foreach ($request->categories as $category) {
			$relation = RelationCategory::where('city_id', $request->city_id)->where('category_id', $category)->first();
			if (!$relation) {
				array_push($temp_array, [
					'city_id' => $request->city_id,
					'category_id' => $category
				]);
			}
		}
		RelationCategory::insert($temp_array);
		return [
			'code' => 201,
			'status' => 'success',
			'message' => 'Relations updated'
		];
	}

	/**
	 * It deletes a relation between a city and a category
	 * 
	 * @param id The id of the relation.
	 * 
	 * @return The destroy method is returning an array with the code, status, and message.
	 */
	public function destroy($id)
	{
		$relation = RelationCategory::find($id);
		if (!$relation) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Relation not found'
			];
		}
		$relation->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Relation deleted'
		];
	}
}
