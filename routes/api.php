<?php

use App\Http\Controllers\Api\V1\DanfeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('v1')->group(function () {
    Route::get('/danfes', [DanfeController::class, 'index']);
    //Route::get('/danfes/{chave}', [DanfeController::class, 'show']);
    Route::post('danfes', [DanfeController::class, 'store']);
    Route::post('/danfes/token', [DanfeController::class, 'getNfeXml']);
    Route::get('/danfes/pdf', [DanfeController::class, 'getNfePdf']);

    //USER
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    //Route::get('/danfes/{id}', 'App\Http\Controllers\DanfeController@show');
    //Route::post('/danfes', 'App\Http\Controllers\DanfeController@store');
    //Route::put('/danfes/{id}', 'App\Http\Controllers\DanfeController@update');
    //Route::delete('/danfes/{id}', 'App\Http\Controllers\DanfeController@destroy');
});
