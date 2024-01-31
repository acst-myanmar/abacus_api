<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\steps\FirstStepController;
use App\Http\Controllers\steps\SecondStepController;
use App\Http\Controllers\StepupController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\steps\ThirdStepController;
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

Route::middleware('auth:sanctum')->post('/upload_img',[StepupController::class, 'upload_img']);
Route::middleware('auth:sanctum')->get('/step_one/{id}',[StepupController::class, 'step_one']);
Route::middleware('auth:sanctum')->post('/step_two',[StepupController::class, 'step_two']);
Route::middleware('auth:sanctum')->post('/step_three',[StepupController::class, 'step_three']);

Route::post('/direct_method',[TestingController::class, 'generateDMQ']);

Route::post('/little_friend',[TestingController::class, 'generateLFQ']);

Route::post('/big_friend',[TestingController::class, 'generateBFQ']);

Route::post('/level_1',[TestingController::class, 'generate_lv1']);


Route::apiResource('first_steps', FirstStepController::class);
Route::apiResource('second_steps', SecondStepController::class);
Route::apiResource('third_steps', ThirdStepController::class);
