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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});






Route::post('register','App\Http\Controllers\api\RegisterController@register');
Route::post('login','App\Http\Controllers\api\RegisterController@login');
Route::post('student/register','App\Http\Controllers\api\RegisterController@register');
Route::post('student/login','App\Http\Controllers\api\RegisterController@login');


// Route::middleware('auth:api')->group(function (){
    //normal routes
    // Route::middleware('UserType:normal')->group(function () {
    Route::post('/quize', 'App\Http\Controllers\api\QuizeController@createQuiz');
    Route::delete('/deletequestion/{id}', 'App\Http\Controllers\api\QuizeController@deleteQuestion');
    Route::delete('/deletequiz/{id}', 'App\Http\Controllers\api\QuizeController@deleteQuiz');
    Route::post('/quizinfo', 'App\Http\Controllers\api\QuizeController@quizinfo');
    Route::put('/updatequiz/{quizId}', 'App\Http\Controllers\api\QuizeController@updateQuiz');
    Route::get('/getquizes', 'App\Http\Controllers\api\QuizeController@getAllQuizzes');
    Route::post('/assignecourses', 'App\Http\Controllers\API\AssigneCourses@assignExam');
    Route::post('/question', 'App\Http\Controllers\api\QuizeController@addQuestion');
    Route::post('add/prof','App\Http\Controllers\api\ProfissorController@addProfessor');
    Route::put('activateQuiz',[App\Http\Controllers\API\ProfissorController::class,'activateQuiz']);
    // });
    //student routes

    // Route::middleware('UserType:student')->group(function () {
    Route::post('add/student',[App\Http\Controllers\API\StudentController::class,'addStudent']);
    Route::post('exam',[App\Http\Controllers\API\QuiezQuestionController::class,'createExam']);
    Route::get('getExam',[App\Http\Controllers\API\StudentController::class,'getExam']);
    Route::post('answer',[App\Http\Controllers\API\StudentController::class,'addAnswer']);
    Route::get('review',[App\Http\Controllers\API\StudentController::class,'reviewAnswers']);
//     });
// });
