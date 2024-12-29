<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
Route::get('/event/get-all', [EventController::class, 'getAllEvents']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/event/add', [EventController::class, 'addEvent']);
    Route::put('/event/edit', [EventController::class, 'editEvent']);
    Route::delete('/event/delete', [EventController::class, 'deleteEvent']);
});

Route::middleware(['auth:sanctum', RoleMiddleware::class.':admin'])->group(function () {
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/add-event', [AdminController::class, 'addEvent']);
    Route::get('/admin/get-all-users', [AdminController::class, 'getAllUsers']);
});
