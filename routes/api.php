<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [UserController::class, "login"]);
Route::post('register', [UserController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{slug}', [CategoryController::class, 'show']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('/popular-products', [ProductController::class, 'popularProducts']);
    Route::get('products/{id}', [ProductController::class,'show']);
    Route::post('products', [ProductController::class,'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Route::apiResource('category', CategoryController::class);
    // Route::apiResource('product', ProductController::class);
});