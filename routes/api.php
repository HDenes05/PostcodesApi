<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CityController;

Route::get('/counties', [CountyController::class, 'index']);
Route::post('/counties', [CountyController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/counties/{id}', [CountyController::class, 'destroy'])->middleware('auth:sanctum');
Route::put('/counties/{name}', [CountyController::class, 'update'])->middleware('auth:sanctum');

Route::get('/cities', [CityController::class, 'index']);
Route::post('/cities', [CityController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/cities/{id}', [CityController::class, 'destroy'])->middleware('auth:sanctum');
Route::put('/cities/{name}', [CityController::class, 'update'])->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/login', [UserController::class, 'login']);


