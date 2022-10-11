<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PositionController;

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

Route::resource('position', PositionController::class);

Route::controller(UserController::class)->group(function() {
	Route::get('/index', 'index')->name('index');
	Route::post('/login', 'login')->name('login');
	Route::post('/register', 'createApplicant')->name('register');
});

Route::group(['middleware' => ['auth:sanctum']], function (){

	Route::group(['middleware' => ['role:admin']], function () {
		Route::resource('admin', AdminController::class);
		Route::resource('exam', ExamController::class);
		Route::resource('question', QuestionController::class);		
	});

	Route::group(['middleware' => ['role:applicant,admin']], function () {
		Route::resource('exam', ExamController::class)->only('index', 'show');
		Route::resource('question', QuestionController::class)->only('show');
		Route::get('/question/all/{exam_id}', [QuestionController::class, 'showQuestionByExam']);
	});
});