<?php

use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\PurchasesController;
use Illuminate\Http\Request;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//--AUTH USER--
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('recover/{email}', 'AuthController@recoverPassword');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    //--PURCHASES--
    Route::get('purchases', 'PurchasesController@index');
    Route::get('purchases/last', 'PurchasesController@last');
    Route::get('purchases/next', 'PurchasesController@next');
    Route::post('purchases', 'PurchasesController@store');
    Route::put('purchases/{id}', 'PurchasesController@update');
    Route::delete('purchases/{id}', 'PurchasesController@destroy');
    
    //--SALES--
    Route::get('sales', 'SalesController@index');
    Route::get('sales/last', 'SalesController@last');
    Route::get('sales/next', 'SalesController@next');
    Route::post('sales', 'SalesController@store');
    Route::put('sales/{id}', 'SalesController@update');
    Route::delete('sales/{id}', 'SalesController@destroy');
    
    //--SUPPLIES--
    Route::get('supplies', 'SuppliesController@index');
    Route::post('supplies', 'SuppliesController@store');
    Route::put('supplies/{id}', 'SuppliesController@update');
    Route::delete('supplies/{id}', 'SuppliesController@destroy');
    
    //--PROVIDERS--
    Route::get('providers', 'ProvidersController@index');
    Route::post('providers', 'ProvidersController@store');
    Route::put('providers/{id}', 'ProvidersController@update');
    Route::delete('providers/{id}', 'ProvidersController@destroy');
    
    //--CLIENTS--
    Route::get('clients', 'ClientsController@index');
    Route::post('clients', 'ClientsController@store');
    Route::put('clients/{id}', 'ClientsController@update');
    Route::delete('clients/{id}', 'ClientsController@destroy');
    
    //--PRODUCTS--
    Route::get('products', 'ProductsController@index');
    Route::post('products', 'ProductsController@store');
    Route::put('products/{id}', 'ProductsController@update');
    Route::delete('products/{id}', 'ProductsController@destroy');
    
    //--PRODUCTIONS--
    Route::get('productions', 'ProductionController@index');
    Route::post('productions', 'ProductionController@store');
    Route::put('productions/{id}', 'ProductionController@update');
    Route::delete('productions/{id}', 'ProductionController@destroy');
    
    //--CATEGORIES--
    Route::get('categories', 'CategoriesController@index');
    
    //GET MEASURES
    Route::get('measures', 'MeasureController@index');
    
    //GET PURCHASES_STATUS
    Route::get('purchases_status', 'PurchasesStatusController@index');
    
    //GET PAYMENT_METHODS
    Route::get('payment_methods', 'PaymentMethodsController@index');
    
    //GET SALES_STATUS
    Route::get('sales_status', 'SalesStatusController@index');
});
