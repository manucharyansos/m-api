<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Guest\GuestController;
use App\Http\Controllers\Api\Product\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\ReviewController;
use App\Http\Controllers\Api\Product\SubcategoryController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['prefix' => 'guests'], function (){
    Route::get('getProducts', [GuestController::class, 'getProducts']);
    Route::get('getCategories', [GuestController::class, 'getCategories']);
    Route::get('/product/{id}', [GuestController::class, 'showProduct']);
    Route::get('/findSubcategoryProducts/{id}', [GuestController::class, 'findSubcategoryWithProducts']);
    Route::get('/findSubcategory/{id}', [GuestController::class, 'findSubcategory']);
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('user',       [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => ['admin']], function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories',CategoryController::class);
        Route::resource('subcategories',SubcategoryController::class);
        Route::delete('/deleteImage/{imageId}', [ProductController::class, 'deleteImage']);
        Route::get('/getReviews/{product}', [ReviewController::class, 'index']);
//        Route::get('/getTest/{id}', [ReviewController::class, 'getReviews']);
        Route::post('/updateProduct/{id}', [ProductController::class, 'updateProduct']);
//        Route::delete('/deleteCategoryImage/{imageId}', [CategoryController::class, 'deleteImage']);
    });

    Route::group(['prefix' => 'reviews'], function (){
        Route::post('/create/{product}', [ReviewController::class, 'store']);
    });

    Route::group(['prefix'=>'users'],function (){
        Route::get('/', [UserController::class, 'index']);
        Route::post('/update/info/{id}', [UserController::class, 'updateUser']);
        Route::post('/store', [UserController::class, 'store']);
        Route::delete('/userDelete/{id}', [UserController::class, 'destroy']);
    });
});

