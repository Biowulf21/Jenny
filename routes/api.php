<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicantQuestionController;
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

Route::get('/position/all', [PositionController::class, 'getAll']);
Route::controller(UserController::class)->group(function() {
	Route::get('/index', 'index')->name('index');
	Route::post('/login', 'login')->name('login');
	Route::post('/register', 'createApplicant')->name('register');
});

Route::group(['middleware' => ['auth:sanctum']], function (){

	//Shared routes
	Route::get('/results/{applicantID}/{examID}', [ApplicantQuestionController::class, 'getExamResults']);
	Route::get('{exam_id}/question/all', [QuestionController::class, 'showQuestionByExam']);	

	//Role-limited routes 
	Route::group(['middleware' => ['role:admin']], function () {
		Route::resource('admin', AdminController::class);
		Route::resource('exam', ExamController::class);
		Route::resource('question', QuestionController::class);
		Route::resource('position', PositionController::class);			
	});

	Route::group(['prefix' => 'applicant', 'middleware' => ['role:applicant']], function() {
		Route::get('/exams', [ExamController::class, 'getApplicantExams']);
		Route::get('/exam/{id}', [ExamController::class, 'getSingleApplicantExam']);
		Route::post('/submit', [ApplicantQuestionController::class, 'applicantChecking']);
	});	
});