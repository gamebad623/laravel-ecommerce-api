<?php

use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register' , [AuthController::class , 'register']);
Route::post('login' , [AuthController::class , 'login']);
Route::middleware('auth:sanctum')->post('logout' , [AuthController::class , 'logout']);


Route::middleware('auth:sanctum')->group(function(){
    
});

// Route::middleware('auth:sanctum')->post('checkout' ,[OrderController::class , 'checkout']);
Route::middleware('auth:sanctum')->group(function(){
    
});

Route::middleware(['auth:sanctum' , 'admin'])
        ->prefix('admin')
        ->group(function(){
            Route::apiResource('orders' , AdminOrderController::class)
            ->only(['index' , 'show' , 'update']);
            Route::apiResource('categories' , CategoryController::class);
            Route::apiResource('products' , ProductController::class);
});
Route::middleware(['auth:sanctum' , 'customer'])->group(function(){

    Route::post('checkout' ,[OrderController::class , 'checkout']);
    Route::get('my-orders' ,[OrderController::class , 'index']);
    Route::get('order-details/{id}' ,[OrderController::class , 'show']);

    Route::get('cart' , [CartController::class , 'index']);
    Route::post('cart/add' , [CartController::class , 'add']);
    Route::put('cart/update/{itemId}' , [CartController::class , 'update']);
    Route::delete('cart/remove/{itemId}' , [CartController::class , 'remove']); 

});
