<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/about','App\Http\Controllers\HomeController@about');

// Route::get('/addfooter', 'App\Http\Controllers\BookController@addfooter');
// Route::get('/', 'HomeController@index')->name('index');
Route::view('/{any}','app')->where('any','.*');
// Route::resource('app', 'HomeController');

Route::post('/media/upload',  'App\Http\Controllers\ImageUploadController@upload');
Route::get('/ckfinder/browser',  'App\Http\Controllers\CkfinderController@browser');