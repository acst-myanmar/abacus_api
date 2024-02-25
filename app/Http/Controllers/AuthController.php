<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SinginRequest;
use App\Http\Requests\SingupRequest;
use App\Http\Requests\SMSRequest;
use App\Http\Resources\UserResource;
use App\Models\Stepup;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function sendOtp($phone, $otp_code)
    {

        $user = User::where('phone', $phone)->first();

        $base_url = 'https://smspoh.com/api/v2/send';;

        if($user){
            $postResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OTP_TOKEN'),
                "Accept" => "application/json",
                'Content-Type' => 'application/json',
            ])
            ->post($base_url, [
                'to' => $phone,
                'message' => $otp_code .  " is your OTP!",
                'sender' => "abacus_mm"
            ]);

            if ($postResponse->successful()) {
                return $postResponse->getBody();
            }else {
                return $postResponse->status();
            }
        }

    }

    public function signUp(SingupRequest $request)
    {
        try {
            $user = new User;

            $first_2 = rand(11, 99);
            $second_2 = rand(11, 99);
            $otp_code = $first_2 . $second_2;

            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->otp_code = $otp_code;
            $user->password = $request->password;
            $user->otp_expired = Carbon::now()->addMinutes(config('abacus.otp_expired'));

            $user->save();

            $this->sendOtp($request->phone, $otp_code);

            // return response()->json([
                return ApiHelper::responseWithSuccess(
                    'successfully created! verfiy with OTP to ativate your account',
                    ['user' => new UserResource($user)]
                    );

            // ]);
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function smsVerification(SMSRequest $request)
    {
        try {
            $user = User::where('otp_code', $request->otp_code)->first();

            if (!$user) {
                return response()->json(['error' => 'wrong otp, please try again!']);
            }

            $token = $user->createToken($user->password . 'AUTH TOKEN')->plainTextToken;

            $step_up = new Stepup;
            $step_up->create([
                'user_id' => $user->id,
            ]);
            $user->update([
                'status' => true,
            ]);

            $now = Carbon::now();

            if ($user && $now->isBefore($user->otp_expired)) {
                $user->update([
                    'otp_code' => null,
                ]);

                return ApiHelper::responseWithSuccess(
                    'successfully activated your account!',
                    ['user' => new UserResource($user)],
                    $token);
            }

            return response()->json(['error' => 'your OTP expired!'], 422);
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id'
            ]);

            if($validator->fails()) {
                return $validator->errors();
            }

            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json(['error' => 'Not Found'], 404);
            }

            $first_2 = rand(11, 99);
            $second_2 = rand(11, 99);
            $otp_code = $first_2 . $second_2;

            $user->update([
                'otp_code' => $otp_code,
                'otp_expired' => Carbon::now()->addMinutes(5),
            ]);

            $this->sendOtp($user->phone, $user->otp_code);


            // return ApiHelper::responseWithSuccess([
            //     'user' => new UserResource($user),
            //     'message' => 'successfully created! verfiy with OTP to ativate your account']);

            return ApiHelper::responseWithSuccess(
                'successfully send new otp',
                ['otp_code' => $otp_code]);
        } catch (Exception $e) {
            return ApiHelper::responseWithUnauthorized($e->getMessage());
        }
    }

    public function signIn(SinginRequest $request)
    {
        try {
            if (
                Auth::attempt(['password' => $request->password, 'phone' => $request->phone, 'status' => true])
            ) {

                $user = Auth::user();
                /** @var \App\Models\User $user */

                $user->tokens()->delete();

                $token = $user->createToken($request->password . 'ATUH_TOKEN')->plainTextToken;

                return ApiHelper::responseWithSuccess(
                    'successfully signin!',
                    new UserResource($user),
                    $token);

            } else {
                return ApiHelper::responseWithUnauthorized('Invalid sigin! Make sure u logout from the other device');
            }
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('phone', $request->phone)->first();

            $first_2 = rand(11, 99);
            $second_2 = rand(11, 99);
            $otp_code = $first_2 . $second_2;

            $user->update([
                'otp_code' => $otp_code,
                'otp_expired' => Carbon::now()->addMinutes(config('abacus.otp_expired')),
            ]);

            $this->sendOtp($request->phone, $otp_code);

            return ApiHelper::responseWithSuccess(
                'use {' . $user->otp_code . '} to change your password',
                [ 'new_otp_code' => $otp_code]
            );
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {

            $user = User::where('otp_code', $request->otp_code)->first();

            $now = Carbon::now();

            if ($user && $now->isBefore($user->otp_expired)) {
                $user->update([
                    'otp_code' => null,
                    'password' => $request->password,
                ]);
                return ApiHelper::responseWithSuccess('successfully changed your password');
            }
            return ApiHelper::responseWithBadRequest(['error' => 'your OTP expired!']);
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function signOut(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => "successfully logout."
        ], 200);
    }
}
