<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

// public routes
// user registration
Route::post('/user/register', [UserController::class, 'register']);
// user login
Route::post('/user/login', [UserController::class, 'login']);
// fetch all events
Route::get('/event/get-all', [EventController::class, 'getAllEvents']);


// authentication routes
Route::middleware('auth:sanctum')->group(function () {
    // add new event
    Route::post('/event/add', [EventController::class, 'addEvent']);
    // edit the event
    Route::put('/event/edit', [EventController::class, 'editEvent']);
    // delete an event
    Route::delete('/event/delete', [EventController::class, 'deleteEvent']);

    Route::get('/event/get-by-user', [EventController::class, 'getEventsByUser']);

    Route::get('/event/get/{id}', [EventController::class, 'getEventById']);
});


// routes for admin specifications
Route::middleware(['auth:sanctum', RoleMiddleware::class.':admin'])->group(function () {
    // delete users by admin
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);
    // add event by admin
    Route::post('/admin/add-event', [AdminController::class, 'addEvent']);
    // get a list of users
    Route::get('/admin/get-all-users', [AdminController::class, 'getAllUsers']);
});
