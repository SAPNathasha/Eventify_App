<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Eventify REST API',
        'status' => 200
    ]);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not found',
        'status' => 'error',
    ], 404);
});
