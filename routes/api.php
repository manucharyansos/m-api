<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Product\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\SubcategoryController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group( function () {
    Route::get('user',       [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
//    Route::group(['middleware' => ['admin']], function () {
//        Route::resource('products', ProductController::class);
//    });
    Route::middleware('admin')->group(function () {
        Route::resource('products', ProductController::class);
    });

    Route::group(['middleware' => ['admin']], function () {
        Route::resource('categories',CategoryController::class);
        Route::resource('subcategories',SubcategoryController::class);
    });

    Route::group(['prefix'=>'users'],function (){
        Route::get('/', [UserController::class, 'index']);
        Route::post('/update/info/{id}', [UserController::class, 'updateUser']);
    });
});

