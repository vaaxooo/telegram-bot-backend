<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api']], function ($route) {

    /* This is a route group that is prefixed with 'auth'. */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', '\App\Http\Controllers\AuthController@login');
        Route::post('logout', '\App\Http\Controllers\AuthController@logout');
        Route::post('refresh', '\App\Http\Controllers\AuthController@refresh');
        Route::post('me', '\App\Http\Controllers\AuthController@me');
    });


    // CITIES
    Route::resource('cities', '\App\Http\Controllers\CityController');

    // CATEGORIES
    Route::get('categories/{category}/visibility', '\App\Http\Controllers\CategoryController@visibility');
    Route::resource('categories', '\App\Http\Controllers\CategoryController');

    // PRODUCTS
    Route::get('products/{product}/visibility', '\App\Http\Controllers\ProductController@visibility');
    Route::resource('products', '\App\Http\Controllers\ProductController');

    // CLIENTS
    Route::get('clients/{client}/banned', '\App\Http\Controllers\ClientController@banned');
    Route::resource('clients', '\App\Http\Controllers\ClientController');
});


/* ########################## API ########################## */

Route::post('client/start', '\App\Http\Controllers\ApiController@start');
Route::get('client/profile', '\App\Http\Controllers\ApiController@getProfile');
Route::get('client/balance', '\App\Http\Controllers\ApiController@getBalance');
Route::post('client/set-referral', '\App\Http\Controllers\ApiController@setReferral');
