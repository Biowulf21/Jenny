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
	Route::get('/results/{applicantID}/{examID}', [ApplicantQuestionController::class, 'fetchExamResults']);
	Route::get('{exam_id}/question/all', [QuestionController::class, 'getQuestionByExam']);	

	//Role-limited routes 
	Route::group(['middleware' => ['role:admin']], function () {
		Route::resource('admin', AdminController::class);
		Route::resource('applicants', ApplicantController::class);
		Route::resource('exam', ExamController::class);
		Route::get('/exam/{id}/results', [ExamController::class, 'showAllExamResults']);
		Route::resource('question', QuestionController::class);
		Route::resource('position', PositionController::class);
		Route::get('/paragraphs/{applicantID}/{examID}', [ApplicantQuestionController::class, 'getParagraphQuestions']);
		Route::post('/check', [ApplicantQuestionController::class, 'adminChecking']);			
	});
	
	Route::group(['prefix' => 'applicant', 'middleware' => ['role:applicant']], function() {
		Route::get('/exams', [ExamController::class, 'getApplicantExams']);
		Route::get('/exam/{id}', [ExamController::class, 'getSingleApplicantExam']);
		Route::get('/position/{id}', [PositionController::class, 'getApplicantPosition']);
		Route::post('/submit', [ApplicantQuestionController::class, 'applicantChecking']);
	});	
});