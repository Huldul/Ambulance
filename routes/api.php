<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\RouteController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/sms', [AuthController::class, 'sendSms']);
Route::post('/verify-sms', [AuthController::class, 'verifySms']);

Route::middleware('auth:api')->group(function () {
    Route::post('/route', [RouteController::class, 'getRoute']);

    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::get('/teams/{id}', [TeamController::class, 'show']);
    Route::put('/teams/{id}', [TeamController::class, 'update']);

    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    Route::post('/emergencies', [EmergencyController::class, 'store']);
    Route::get('/emergencies', [EmergencyController::class, 'index']);
    Route::get('/emergencies/{id}', [EmergencyController::class, 'show']);
    Route::put('/emergencies/{id}', [EmergencyController::class, 'update']);
});
