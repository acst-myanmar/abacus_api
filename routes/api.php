<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionSettingController;
use App\Http\Controllers\RoomController;
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


Route::group(['middleware' => 'auth:sanctum'], function() {

    // Route::post('/upload_img',[StepupController::class, 'upload_img']);
    // Route::get('/step_one/{id}',[StepupController::class, 'step_one']);
    // Route::post('/step_two',[StepupController::class, 'step_two']);
    // Route::post('/step_three',[StepupController::class, 'step_three']);

    Route::get('/resources',[StepupController::class, 'resources']);
    Route::post('/submited_answers',[StepupController::class, 'submited_answers']);

    Route::post('/direct_method',[TestingController::class, 'generateDMQ']);
    Route::post('/little_friend',[TestingController::class, 'generateLFQ']);
    Route::post('/big_friend',[TestingController::class, 'generateBFQ']);
    Route::post('/level_1',[TestingController::class, 'generate_lv1']);


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/signout',[AuthController::class, 'signout']);


    Route::post('/send_notification',[NotificationController::class, 'SendNotification']);
    Route::post('/set_question_setting',[QuestionSettingController::class, 'setQuestionSetting']);
    Route::get('/set_question_setting/{id}',[QuestionSettingController::class, 'setting']);
    Route::post('/set_question',[QuestionSettingController::class, 'setQuestion']);
    Route::get('/one_to_one/{id}',[RoomController::class, 'OneToOne']);
    Route::post('/create_group',[RoomController::class, 'CreateGroup']);
    Route::post('/send_message',[RoomController::class, 'SendMessage']);
});
Route::get('/groups/{id}',[RoomController::class, 'UserGroups']);
Route::get('/friends/{id}',[RoomController::class, 'UserFriends']);
Route::get('/messages/{id}',[RoomController::class, 'GetMessages']);


Route::apiResource('first_steps', FirstStepController::class);
Route::apiResource('second_steps', SecondStepController::class);
Route::apiResource('third_steps', ThirdStepController::class);






