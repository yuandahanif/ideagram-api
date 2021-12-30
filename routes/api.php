<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;

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


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('comments/{id}', [CommentController::class, 'index']);
    Route::post('comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'delete']);
    Route::post('ideas/{idea}/image', [IdeaController::class, 'image']);
    Route::resource('ideas', IdeaController::class);
    Route::resource('feedback', FeedbackController::class);
    Route::resource('donations', DonationController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('users/idea', [UserController::class, 'idea']);
    Route::get('users/{user}/idea', [UserController::class, 'showIdea']);
});
