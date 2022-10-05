<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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

Route::controller(UserController::class)->group(function() {
	Route::get('/index', 'index')->name('index');
	Route::post('/login', 'login')->name('login');
});

Route::resource('admin', AdminController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
