<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("categories",[CategoryController::class, 'getAll']);
Route::post("categories/create",[CategoryController::class, 'create']);
Route::post("categories/update/{id}",[CategoryController::class, 'update']);
Route::delete("categories/delete/{id}",[CategoryController::class, 'destroy']);