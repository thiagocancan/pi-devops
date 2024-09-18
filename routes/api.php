<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReminderController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function() {

    // user
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // reminders
    Route::get('/reminders/{user_id}', [ReminderController::class, 'index']); // all user reminders
    Route::post('/reminders', [ReminderController::class, 'store']); // create reminder
    Route::get('/reminders/{id}', [ReminderController::class, 'show']); // get single reminder
    Route::put('/reminders/{id}', [ReminderController::class, 'update']); // update reminder
    Route::delete('/reminders/{id}', [ReminderController::class, 'destroy']); // delete reminder

});