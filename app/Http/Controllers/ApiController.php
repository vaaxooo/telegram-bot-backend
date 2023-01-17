<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;

class ApiController extends Controller
{

	private $apiService;

	public function __construct(Request $request)
	{
		$this->apiService = new ApiService();
	}

	/* ########################## CLIENT INFO ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function start(Request $request)
	{
		return response()->json($this->apiService->start($request));
	}

	/**
	 * It returns a JSON response of the result of the `getProfile` function in the `ApiService` class
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getProfile(Request $request)
	{
		return response()->json($this->apiService->getProfile($request));
	}

	/**
	 * > This function returns the balance of the account
	 * 
	 * @return A JSON response.
	 */
	public function getBalance(Request $request)
	{
		return response()->json($this->apiService->getBalance($request));
	}

	/**
	 * It checks if the user is banned
	 * 
	 * @param Request request The request object
	 * 
	 * @return A JSON response.
	 */
	public function checkBan(Request $request)
	{
		return response()->json($this->apiService->checkBan($request));
	}


	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function setReferral(Request $request)
	{
		return response()->json($this->apiService->setReferral($request));
	}

	/* ########################## ORDERS ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function createOrder(Request $request)
	{
		return response()->json($this->apiService->createOrder($request));
	}

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getOrders(Request $request)
	{
		return response()->json($this->apiService->getOrders($request));
	}

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getOrder(Request $request)
	{
		return response()->json($this->apiService->getOrder($request));
	}

	/* ########################## CITIES ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getCities(Request $request)
	{
		return response()->json($this->apiService->getCities($request));
	}

	/* ########################## DISTRICTS ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getDistricts(Request $request)
	{
		return response()->json($this->apiService->getDistricts($request));
	}

	/* ########################## CATEGORIES ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getCategories(Request $request)
	{
		return response()->json($this->apiService->getCategories($request));
	}

	/* ########################## PRODUCTS ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getProducts(Request $request)
	{
		return response()->json($this->apiService->getProducts($request));
	}

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function getProductById($product)
	{
		return response()->json($this->apiService->getProductById($product));
	}

	/* ########################## REVIEWS ########################## */

	/**
	 * It returns a JSON response of the result of the `getReviews` function in the `ApiService` class
	 * 
	 * @param Request request The request object
	 * 
	 * @return A JSON response.
	 */
	public function getReviews(Request $request)
	{
		return response()->json($this->apiService->getReviews($request));
	}

	/* ########################## TRANSACTIONS ########################## */

	/**
	 * It takes a request, passes it to the apiService, and returns the response as json
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function createTransaction(Request $request)
	{
		return response()->json($this->apiService->createTransaction($request));
	}

	/**
	 * It takes a request, passes it to the apiService, and returns the response as a json object
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function createKunaTransaction(Request $request)
	{
		return response()->json($this->apiService->createKunaTransaction($request));
	}
}
