<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group( function () {
    Route::get('user',       [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::group(['middleware' => ['admin']], function () {
        Route::resource('products', ProductController::class);
    });

    Route::group(['middleware' => ['admin']], function () {
        Route::resource('categories',CategoryController::class);
    });
});

