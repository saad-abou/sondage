<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

//getWelcome
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');
//getQuestion
Route::get('/Questions', [App\Http\Controllers\HomeController::class, 'question'])->name('/');

Route::resource('questions', QuestionsController::class);
Route::resource('reponses','App\Http\Controllers\ReponsesController');
//getAllQuestions
Route::get('getquestions',[QuestionController::class,'getAll']);
//addQuestion
Route::post('addquestions',[QuestionController::class,'addQuestion']);
//deleteQuestion
Route::post('deleteQuestion',[QuestionController::class,'deleteQuestion']);
//showQuestion
Route::post('updateSituation',[QuestionController::class,'updateSituation']);
Route::post('updateReponses',[ReponseController::class,'updateReponses']);
Route::get('updateUsersVote',[UserController::class,'updateUsersVote']);
//addReponses
Route::post('addreponses',[ReponseController::class,'addReponses']);
//getAllResponses
Route::get('/getresponses',[ReponseController::class,'getAll']);
//getUser
Route::get('getuser',[UserController::class,'getUser']);
//getQuetionOn
Route::get('getQuestionOn',[QuestionController::class,'getQuestionOn']);
//vote
Route::post('addVoteQuestion',[QuestionController::class,'addVoteQuestion']);
Route::post('addVoteReponse',[ReponseController::class,'addVoteReponse']);
Route::post('userVote',[UserController::class,'userVote']);
//diffuser
Route::get('diffuser',[UserController::class,'diffuser']);
//diffuser
Route::get('diffuserVote',[UserController::class,'diffuserVote']);
//addReponse
Route::post('addReponse',[ReponseController::class,'addReponse']);
//addMultiReponses
Route::post('addMultivotes',[ReponseController::class,'addMultivotes']);
//addMultiVotesQuest
Route::post('addMultiVoteQuestion',[QuestionController::class,'addMultiVoteQuestion']);
//updateQuestion
Route::post('updateQuestion',[QuestionController::class,'updateQuestion']);
//updateReponses
Route::post('updatReponses',[ReponseController::class,'updatReponses']);
//addTime
Route::post('addTime',[QuestionController::class,'addTime']);
//stopShow
Route::get('stopShow',[QuestionController::class,'stopShow']);







