<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\RoundController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BackupController;

// Public auth routes (login only)
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected auth routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

// Protected resource routes
Route::middleware('auth:sanctum')->group(function () {
    // Teams - All authenticated users can create/update/delete
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::put('/teams/{team}', [TeamController::class, 'update']);
    Route::delete('/teams/{team}', [TeamController::class, 'destroy']);

    // Games - Delete is admin only
    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games', [GameController::class, 'store']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    Route::put('/games/{game}', [GameController::class, 'update']);
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->middleware('admin');
    Route::get('/games/{game}/rounds', [RoundController::class, 'gameRounds']);

    // Rounds - Delete is admin only
    Route::get('/rounds', [RoundController::class, 'index']);
    Route::post('/rounds', [RoundController::class, 'store']);
    Route::put('/rounds/{round}', [RoundController::class, 'update']);
    Route::delete('/rounds/{round}', [RoundController::class, 'destroy'])->middleware('admin');

    // Users (admin only) - includes registration
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('admin');
    Route::get('/users', [UserController::class, 'index'])->middleware('admin');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('admin');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('admin');

    // Backup (admin only)
    Route::post('/backup/export', [BackupController::class, 'export'])->middleware('admin');
    Route::post('/backup/import', [BackupController::class, 'import'])->middleware('admin');
});
