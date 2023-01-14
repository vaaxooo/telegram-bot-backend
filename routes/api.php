<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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

Route::post('/bot/get-updates', function () {
    $updates = Telegram::getUpdates();
    return (json_encode($updates));
});

Route::group(['middleware' => ['api']], function ($route) {

    /* This is a route group that is prefixed with 'auth'. */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', '\App\Http\Controllers\AuthController@login');
        Route::post('logout', '\App\Http\Controllers\AuthController@logout');
        Route::post('refresh', '\App\Http\Controllers\AuthController@refresh');
        Route::post('me', '\App\Http\Controllers\AuthController@me');
    });


    /* This is a route group that is prefixed with 'admin'. */
    Route::group(['prefix' => 'admin'], function () {
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

        // ORDERS
        Route::post('orders/{order}/set-status', '\App\Http\Controllers\OrderController@setStatus');
        Route::resource('orders', '\App\Http\Controllers\OrderController');

        // RELATIONS
        Route::resource('relations', '\App\Http\Controllers\RelationCategoryController');

        // MAILING
        Route::post('mailing/send', '\App\Http\Controllers\MailingController@send');
        Route::post('mailing/send-by-telegram-id', '\App\Http\Controllers\MailingController@sendByTelegramId');
    });
});


/* ########################## API ########################## */

// CLIENTS
Route::post('client/start', '\App\Http\Controllers\ApiController@start');
Route::get('client/profile', '\App\Http\Controllers\ApiController@getProfile');
Route::get('client/balance', '\App\Http\Controllers\ApiController@getBalance');
Route::post('client/set-referral', '\App\Http\Controllers\ApiController@setReferral');

// ORDERS
Route::get('orders', '\App\Http\Controllers\ApiController@getOrders');
Route::get('orders/{order}', '\App\Http\Controllers\ApiController@getOrder');
Route::post('orders/create', '\App\Http\Controllers\ApiController@createOrder');

// CITIES
Route::get('cities', '\App\Http\Controllers\ApiController@getCities');

// CATEGORIES
Route::get('categories', '\App\Http\Controllers\ApiController@getCategories');

// PRODUCTS
Route::get('products', '\App\Http\Controllers\ApiController@getProducts');
Route::get('products/{product}', '\App\Http\Controllers\ApiController@getProductById');
