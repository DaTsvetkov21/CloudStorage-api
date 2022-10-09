<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', [AuthController::class, 'index']);

Route::post('/auth', [AuthController::class, 'registration']);
