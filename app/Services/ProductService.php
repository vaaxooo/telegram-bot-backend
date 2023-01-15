<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductService
{

	/**
	 * It gets all the products from the database and returns them in a JSON format
	 * 
	 * @return An array with the code, status, and products.
	 */
	public function index()
	{
		$products = Product::with('category')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $products
		];
	}

	/**
	 * It validates the request, checks if the request has an image, uploads the image, and creates a new
	 * product
	 * 
	 * @param request This is the request object that contains all the data that was sent to the server.
	 * 
	 * @return An array with a code, status, and product.
	 */
	public function store($request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'price' => 'required',
				'category_id' => 'required',
				'stock' => 'required',
				'description' => 'required',
				'slug' => 'required|unique:products'
			]);
			if ($validator->fails()) {
				return [
					'code' => 400,
					'status' => 'error',
					'errors' => $validator->errors()
				];
			}
			if ($request->hasFile('image')) {
				$image = $request->file('image');
				$filename = time() . '.' . $image->getClientOriginalExtension();
				$location = public_path('images/' . $filename);
				Image::make($image)->resize(800, 400)->save($location);
			}
			$product = Product::create([
				'name' => $request->name,
				'price' => $request->price,
				'category_id' => $request->category_id,
				'stock' => $request->stock,
				'description' => $request->description,
				'image' => '//' . $_SERVER['HTTP_HOST'] . '/images/' . $filename,
				'slug' => $request->slug
			]);
			return [
				'code' => 200,
				'status' => 'success',
				'message' => 'Product created successfully',
				'data' => $product
			];
		} catch (\Exception $e) {
			return [
				'code' => 400,
				'status' => 'error',
				'errors' => $e->getMessage()
			];
		}
	}

	/**
	 * It returns an array with three keys: `code`, `status`, and `product`
	 * 
	 * @param product The product object that was retrieved from the database.
	 * 
	 * @return An array with the code, status, and product.
	 */
	public function show($product)
	{
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $product
		];
	}

	/**
	 * It validates the request, checks if the request has an image, uploads the image, and updates the
	 * product
	 * 
	 * @param request This is the request object that contains all the data that was sent to the server.
	 * @param product The product object that was retrieved from the database.
	 * 
	 * @return An array with a code, status, and product.
	 */
	public function update($request, $product)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'price' => 'required',
			'category_id' => 'required',
			'stock' => 'required',
			'description' => 'required',
			'slug' => 'required|unique:products,slug,' . $product->id . ',id'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'errors' => $validator->errors()
			];
		}
		if ($request->hasFile('image')) {
			$image = $request->file('image');
			$filename = time() . '.' . $image->getClientOriginalExtension();
			$location = public_path('images/' . $filename);
			Image::make($image)->resize(800, 400)->save($location);
			$product->image = '//' . $_SERVER['HTTP_HOST'] . '/images/' . $filename;
		}
		$product->name = $request->name;
		$product->price = $request->price;
		$product->category_id = $request->category_id;
		$product->stock = $request->stock;
		$product->description = $request->description;
		$product->slug = $request->slug;
		$product->save();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Product updated successfully',
			'data' => $product
		];
	}

	/**
	 * It deletes the product from the database
	 * 
	 * @param product The product object that was retrieved from the database.
	 * 
	 * @return An array with a code and status.
	 */
	public function destroy($product)
	{
		Product::where('id', $product->id)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Product deleted successfully'
		];
	}

	/**
	 * > It updates the visibility of a product
	 * 
	 * @param product The product object that was passed to the function.
	 * 
	 * @return an array with the key 'code' and the value 200, the key 'status' and the value 'success',
	 * and the key 'product' and the value .
	 */
	public function visibility($product)
	{
		$product = Product::find($product);
		Product::where('id', $product->id)->update(['is_active' => !$product->is_active]);
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $product,
			'message' => 'Product visibility updated successfully'
		];
	}
}
