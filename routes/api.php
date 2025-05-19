<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// category
Route::get('/category', 'App\Http\Controllers\CategoryController@index');
Route::get('/category/{id}', 'App\Http\Controllers\CategoryController@show');
Route::post('/category_update/{id}', 'App\Http\Controllers\CategoryController@update');
Route::post('/category_add',  'App\Http\Controllers\CategoryController@add');

Route::get('/cate_product','App\Http\Controllers\HomeController@cate_product');
// 


Route::get('/product_detail/{id}', 'App\Http\Controllers\HomeController@show_detail');

Route::get('/user_show/{id}', 'App\Http\Controllers\HomeController@show');
Route::get('/product_all','App\Http\Controllers\HomeController@index_all');
Route::get('/category_all','App\Http\Controllers\HomeController@category_all');

// Route::delete('/user_delete/{id}', 'App\Http\Controllers\HomeController@destroy');

Route::post('/user_update/{id}', 'App\Http\Controllers\HomeController@update');
Route::post('/user_add',  'App\Http\Controllers\HomeController@add');
Route::get('/search', 'App\Http\Controllers\HomeController@sort');
Route::get('/flights',  'App\Http\Controllers\HomeController@index');

// Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
// });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('auth:api')->get('/users_all',  'App\Http\Controllers\HomeController@index_all');

// routes/api.php
// Route::post('/upload-image', 'App\Http\Controllers\ImageUploadController@upload'); 


Route::middleware('auth:api')->delete('/user_delete/{id}', 'App\Http\Controllers\HomeController@destroy');
Route::middleware('auth:api')->delete('/category_delete/{id}', 'App\Http\Controllers\CategoryController@destroy');