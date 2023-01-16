<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class OrderService
{
	private const STATUSES = [
		'pending',
		'approved',
		'canceled'
	];

	/**
	 * It returns an array with a code, status, and data
	 * 
	 * @return An array with a code, status, and data.
	 */
	public function index()
	{
		$orders = Order::with('client', 'product')->orderBy('id', 'desc')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $orders
		];
	}

	/**
	 * It returns a JSON response with the status code of 200 and the data of the order
	 * 
	 * @param Order order The model that we want to show.
	 * 
	 * @return An array with the key 'data' and the value of the  variable.
	 */
	public function show($order)
	{
		$order->client = Client::where('id', $order->client_id)->first();
		$order->product = Product::where('id', $order->product_id)->first();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $order
		];
	}

	/**
	 * It deletes the order from the database.
	 * 
	 * @param order The order object that was passed to the route.
	 * 
	 * @return An array with a code, status, and data.
	 */
	public function destroy($order)
	{
		Order::where('id', $order->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Order deleted successfully'
		];
	}

	/**
	 * It updates the status of an order
	 * 
	 * @param request The request object
	 * @param order The order object
	 * 
	 * @return return [
	 * 			'code' => 200,
	 * 			'status' => 'success',
	 * 			'data' => 'Order status updated successfully'
	 * 		];
	 */
	public function setStatus($request, $order)
	{
		if (!in_array($request->status, self::STATUSES)) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => 'Invalid status'
			];
		}
		Order::where('id', $order->id)->update(['status' => $request->status]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Order status updated successfully'
		];
	}
}
