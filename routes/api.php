<?php


use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/signin',[AuthController::class, 'signin']);
Route::post('/sms_verification',[AuthController::class, 'sms_verification']);
Route::get('/resend_otp/{id}',[AuthController::class, 'resendOTP']);
Route::post('/signup',[AuthController::class, 'signup']);
Route::post('/forgot_password',[AuthController::class, 'forgot_password']);
Route::post('/reset_password',[AuthController::class, 'reset_password']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


