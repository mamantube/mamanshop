<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [UserController::class, "register"]);
Route::post("/login", [UserController::class, "login"]);
Route::patch("/users/{id}", [UserController::class, "updateUser"]);
Route::get("products/{id}", [ProductController::class, "product"]);
Route::get("/products", [ProductController::class, "allProducts"]);


Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::get("/users", [UserController::class, "allUser"]);
    Route::get("/users/{id}", [UserController::class, "user"]);
    Route::delete("/users/{id}", [UserController::class, "deleteUser"]);

    Route::prefix('products')->group(function () {
        Route::post("/add", [ProductController::class, "addProduct"]);
        Route::post("/update/{id}", [ProductController::class, "updateProduct"]);
        Route::delete("/{id}", [ProductController::class, "deleteProduct"]);
    });

    Route::get("/cart", [CartController::class, "allCart"]);
    Route::post("/cart", [CartController::class, "addToCart"]);
    Route::delete("/cart/{id}", [CartController::class, "removeFromCart"]);
    Route::get("/cart/{id}", [CartController::class], "cartById");


});