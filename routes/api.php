<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SaleRecordController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->group(function(){
//     Route::apiResource('books',BookController::class);
//     Route::apiResource('sale_records',SaleRecordController::class);
//     Route::apiResource('customers',CustomerController::class);
//     Route::apiResource('categories',CategoryController::class);
// });
// Route::controller(AuthController::class)->group(function(){
//     Route::post('/login','login');
// });
