<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\RelationCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;


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
		try {
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
		} catch (\Exception $e) {
			return [
				'code' => 500,
				'status' => 'error',
				'message' => 'Internal server error',
				'errors' => $e->getMessage()
			];
		}
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
		try {
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
			if ($client->referral != null) {
				return [
					'code' => 403,
					'status' => 'error',
					'message' => 'Referral already set',
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
			Client::where('telegram_id', $request->telegram_id)->update([
				'referral' => $request->referral,
				'balance' => (int) $client->balance + 25
			]);
			return [
				'code' => 200,
				'status' => 'success',
				'message' => 'Referral set',
				'data' => $client
			];
		} catch (\Exception $e) {
			return [
				'code' => 500,
				'status' => 'error',
				'message' => 'Internal server error',
				'errors' => $e->getMessage()
			];
		}
	}

	/* ########################## ORDERS ########################## */

	/**
	 * It gets all orders of a client by his telegram id
	 * 
	 * @param request The request object.
	 * 
	 * @return An array of orders.
	 */
	public function getOrders($request)
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
		$client = Client::where('telegram_id', $request->telegram_id)->first();
		if (!$client) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Client not found',
				'data' => $client
			];
		}
		$orders = Order::where('client_id', $client->id)->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $orders
		];
	}

	/**
	 * It gets an order by its id and the client's telegram id
	 * 
	 * @param request The request object
	 */
	public function getOrder($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
			'order_id' => 'required',
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
		$order = Order::where('client_id', $client->id)->where('id', $request->order_id)->first();
		if (!$order) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Order not found',
				'data' => $order
			];
		}
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $order
		];
	}

	/**
	 * It creates a new order.
	 * 
	 * @param request The request object.
	 * 
	 * @return The return is an array with the following keys:
	 */
	public function createOrder($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
			'product_id' => 'required',
			'quantity' => 'required',
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
		$product = Product::where('id', $request->product_id)->first();
		if (!$product) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Product not found',
				'data' => $product
			];
		}
		if ($product->stock < $request->quantity) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Not enough stock',
				'data' => $product
			];
		}
		if ($client->balance < $product->price * $request->quantity) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Not enough balance',
				'data' => $product
			];
		}

		$order = Order::create([
			'client_id' => $client->id,
			'product_id' => $product->id,
			'quantity' => $request->quantity,
			'total_price' => $product->price * $request->quantity,
			'status' => 'pending',
		]);
		$client->balance = (int) $client->balance - $product->price * $request->quantity;
		$client->save();
		$product->stock = (int) $product->stock - $request->quantity;
		$product->save();

		$temp_client = $client->nickname ? '@' . $client->nickname : $client->telegram_id;
		$tMessage = "📦 *Новый заказ* 📦" . PHP_EOL;
		$tMessage .= "👤 *Клиент:* " . $temp_client . PHP_EOL;
		$tMessage .= "📦 *Товар:* " . $product->name . PHP_EOL;
		$tMessage .= "📦 *Количество:* " . $request->quantity . PHP_EOL;
		$tMessage .= "📦 *Общая сумма:* " . $product->price * $request->quantity . " ₴" . PHP_EOL;

		Telegram::sendMessage([
			'chat_id' => env('TELEGRAM_SUPPORT_CHAT_ID'),
			'text' => $tMessage,
			'parse_mode' => 'Markdown',
		]);

		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Order created',
			'data' => $order
		];
	}

	/* ########################## CITIES ########################## */

	/**
	 * It gets all the cities.
	 * 
	 * @return The return is an array with the following keys:
	 */
	public function getCities()
	{
		$cities = City::get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $cities
		];
	}

	/* ########################## CATEGORIES ########################## */

	/**
	 * It gets all the categories that are active and related to a city
	 * 
	 * @param request The request object.
	 */
	public function getCategories($request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'city_id' => 'required',
			]);
			if ($validator->fails()) {
				return [
					'code' => 400,
					'status' => 'error',
					'message' => 'Bad request',
					'errors' => $validator->errors()
				];
			}
			$city = City::where('id', $request->city_id)->first();
			if (!$city) {
				return [
					'code' => 404,
					'status' => 'error',
					'message' => 'City not found',
					'data' => $city
				];
			}
			$categories = RelationCategory::with('category')
				->where('city_id', $request->city_id)
				->pluck('category_id')->toArray();
			$categories = Category::select('id', 'name', 'slug')->whereIn('id', $categories)->get();
			return [
				'code' => 200,
				'status' => 'success',
				'data' => [
					'city' => $city,
					'categories' => $categories
				]
			];
		} catch (\Exception $e) {
			return [
				'code' => 500,
				'status' => 'error',
				'message' => 'Internal server error',
				'errors' => $e->getMessage()
			];
		}
	}

	/* ########################## PRODUCTS ########################## */

	/**
	 * It gets all the products.
	 * 
	 * @return The return is an array with the following keys:
	 */
	public function getProducts($request)
	{
		$validator = Validator::make($request->all(), [
			'category_id' => 'required',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Bad request',
				'errors' => $validator->errors()
			];
		}
		$category = RelationCategory::with('category')->where('category_id', $request->category_id)->first();
		if (!$category) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Category not found',
				'data' => $category
			];
		}
		$products = Product::where('category_id', $request->category_id)
			->where('is_active', true)
			->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => [
				'category' => $category,
				'products' => $products
			]
		];
	}

	/**
	 * It returns a product if it exists, otherwise it returns an error
	 * 
	 * @param request The request object
	 * 
	 * @return An array with the following keys:
	 * - code
	 * - status
	 * - message
	 * - data
	 */
	public function getProductById($product)
	{
		$product = Product::where('id', $product)
			->where('is_active', true)
			->first();
		if (!$product) {
			return [
				'code' => 404,
				'status' => 'error',
				'message' => 'Product not found',
				'data' => $product
			];
		}
		$category = Category::select('id', 'name', 'slug')->where('id', $product->category_id)->first();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => [
				'category' => $category,
				'product' => $product
			]
		];
	}

	/* ########################## TRANSACTIONS ########################## */

	/**
	 * It creates a transaction for a client
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with the following keys:
	 */
	public function createTransaction($request)
	{
		$validator = Validator::make($request->all(), [
			'telegram_id' => 'required',
			'amount' => 'required|numeric',
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
		$transaction = Transaction::create([
			'client_id' => $client->id,
			'amount' => $request->amount
		]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Transaction created successfully',
			'data' => $transaction
		];
	}

	public function createKunaTransaction($request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'telegram_id' => 'required',
				'amount' => 'required|numeric'
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


			$apiKey = env('KUNA_API_KEY');
			$apiSecret = env('KUNA_API_SECRET');



			$apiPath = '/v3/auth/kuna_codes/issued-by-me';
			$nonce = Carbon::now()->timestamp;
			$body = [];
			$signature = $apiPath . $nonce . json_encode($body);

			$sig = hash_hmac('sha384', $signature, $apiSecret);
			// hex $sig
			$shex = '';
			for ($i = 0; $i < strlen($sig); $i += 2) {
				$shex .= chr(hexdec(substr($sig, $i, 2)));
			}



			return [
				'kun-nonce' => $nonce,
				'kun-apikey' => $apiKey,
				'kun-signature' => $shex
			];

			$response = Http::withHeaders([
				'kun-nonce' => $nonce,
				'kun-apikey' => $apiKey,
				'kun-signature' => $shex
			])->post('https://api.kuna.io' . $apiPath, $body);

			return $response->json();


			$transaction = Transaction::create([
				'client_id' => $client->id,
				'amount' => $request->amount
			]);
		} catch (\Exception $e) {
			return [
				'code' => 500,
				'status' => 'error',
				'message' => 'Internal server error',
				'errors' => $e->getMessage()
			];
		}
	}
}
