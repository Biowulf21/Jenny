<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantController;

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
	Route::post('/register', 'createApplicant')->name('register');
});

Route::group(['middleware' => ['auth:sanctum']], function (){

	Route::group(['middleware' => ['role:admin']], function () {
		Route::resource('admin', AdminController::class);
	});

	// applicant
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
