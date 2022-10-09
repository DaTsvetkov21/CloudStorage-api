<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', [AuthController::class, 'index']);

Route::post('/singup', [AuthController::class, 'registration']);
Route::post('/singin', [AuthController::class, 'authorization']);
