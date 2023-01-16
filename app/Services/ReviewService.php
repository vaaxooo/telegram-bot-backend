<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

class ReviewService
{

	/**
	 * It returns a JSON response with a 200 status code, a success status, and the data from the database
	 * 
	 * @return An array with the code, status, and data.
	 */
	public function index()
	{
		$reviews = Review::orderBy('id', 'desc')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $reviews
		];
	}

	/**
	 * It creates a new review and returns the newly created review
	 * 
	 * @param request The request object
	 * 
	 * @return An array with a code, status, and data.
	 */
	public function store($request)
	{
		$validator = Validator::make($request->all(), [
			'comment' => 'required|string'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'The comment field is required.'
			];
		}
		$review = Review::create([
			'comment' => $request->comment
		]);
		return [
			'code' => 201,
			'status' => 'success',
			'data' => $review
		];
	}

	/**
	 * It deletes the review with the given ID
	 * 
	 * @param review The review object that was passed to the route.
	 * 
	 * @return An array with a code, status, and message.
	 */
	public function destroy($review)
	{
		Review::where('id', $review->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'The review has been deleted.'
		];
	}
}
