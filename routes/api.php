<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
Route::get('/event/get-all', [EventController::class, 'getAllEvents']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/event/add', [EventController::class, 'addEvent']);
    Route::put('/event/edit', [EventController::class, 'editEvent']);
    Route::delete('/event/delete', [EventController::class, 'deleteEvent']);
});
