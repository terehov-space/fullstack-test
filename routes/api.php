<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\BoardController;
use \App\Http\Controllers\TaskController;

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

Route::group(['middleware' => 'api',], function () {
    Route::group(['prefix' => 'auth',], function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::post('register', [AuthController::class, 'register']);

        Route::post('logout', [AuthController::class, 'logout']);

        Route::post('refresh', [AuthController::class, 'refresh']);

        Route::post('me', [AuthController::class, 'me']);
    });

    Route::group(['prefix' => 'boards',], function () {
        Route::get('/', [BoardController::class, 'get']);

        Route::post('/', [BoardController::class, 'create']);

        Route::post('/{id}', [BoardController::class, 'update']);

        Route::delete('/{id}', [BoardController::class, 'delete']);
    });

    Route::group(['prefix' => 'tasks',], function () {
        Route::post('/', [TaskController::class, 'create']);

        Route::post('/{id}', [TaskController::class, 'update']);

        Route::delete('/{id}', [TaskController::class, 'delete']);
    });
});
