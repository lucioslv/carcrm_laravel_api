<?php

use App\Http\Controllers\Auth\AuthController;
#use App\Http\Controllers\DataScraping;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'store']);

#Route::get('/olx/{id}', [DataScraping::class, 'index']);
