<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryService
{

	/**
	 * It returns a JSON response with a 200 status code, a success status, and the data from the
	 * categories table
	 * 
	 * @return An array with the code, status, and data.
	 */
	public function index()
	{
		$categories = Category::orderBy('id', 'desc')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $categories
		];
	}

	/**
	 * It validates the request, creates a new category, and returns a response
	 * 
	 * @param request The request object
	 * 
	 * @return An array with three keys: code, status, and data.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'slug' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'errors' => $validator->errors()
			];
		}
		$category = Category::create($request->all());
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Category created successfully',
			'data' => $category
		];
	}

	/**
	 * It returns an array with a code, status, and data
	 * 
	 * @param category The name of the parameter that will be passed to the method.
	 * 
	 * @return An array with the code, status, and data.
	 */
	public function show($category)
	{
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $category
		];
	}

	/**
	 * It updates a category
	 * 
	 * @param request The request object
	 * @param category The category object that we want to update.
	 * 
	 * @return An array with the code, status, and data.
	 */
	public function update($request, $category)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'slug' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'errors' => $validator->errors()
			];
		}
		$category->update($request->all());
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Category updated successfully',
			'data' => $category
		];
	}

	/**
	 * It deletes a category from the database.
	 * 
	 * @param category The category object that was passed to the route.
	 * 
	 * @return The category that was deleted.
	 */
	public function destroy($category)
	{
		Category::where('id', $category->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Category deleted successfully',
			'data' => $category
		];
	}

	/**
	 * It updates the visibility of a category.
	 * 
	 * @param category The category object that was passed to the function.
	 * 
	 * @return an array with the following keys:
	 */
	public function visibility($category)
	{
		Category::where('id', $category->id)->update(['is_active' => !$category->visible]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Category visibility updated successfully',
			'data' => $category
		];
	}
}
