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
use GuzzleHttp\Client;

class AuthController extends Controller
{

    public function sendOTP($phone, $otp_code)
    {

        $user = User::where('phone', $phone)->first();

        $client = new Client();

        $token = 'ZugHBnKarsVKdlii2GBW0FcWBedUlxLmiW2c8Kdsvmr1bLF2G9AdvaThtsdRKGoV';

        $base_url = 'https://smspoh.com/api/v2/send';

        $headers = [
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $token,
        ];

        $data = [
            'to' => $phone,
            'message' => $otp_code .  " is your OTP!",
            'sender' => "abacus_mm"
        ];

        $postResponse = $client->post($base_url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        if ($postResponse->getStatusCode() == 200) {
            // $response = $postResponse->getBody();
            return $postResponse->getStatusCode();
        }
    }

    public function signup(SingupRequest $request)
    {
        try{
            $user = new User;

            $first_2 = rand(11, 99);
            $second_2 = rand(11, 99);
            $otp_code = $first_2 . $second_2;

            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->otp_code = $otp_code;
            $user->password = $request->password;
            $user->otp_expired = Carbon::now()->addMinutes(5);

            $user->save();

            $this->sendOTP($request->phone, $otp_code);

            return response()->json(['user' => new UserResource($user), 'message' => 'successfully created! verfiy with OTP to ativate your account']);
        }catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }

    }

    public function sms_verification(SMSRequest $request)
    {
        try{
            if ($request->otp_code == null) {
                return response()->json(['??' => "nice try!"]);
            }

            $user = User::where('otp_code', $request->otp_code)->first();


            $token = $user->createToken($user->password . 'AUTH TOKEN')->plainTextToken;

            $step_up = new Stepup;
            $step_up->create([
                'user_id' => $user->id,
            ]);
            $user->update([
                'status' => 1,
            ]);

            $now = Carbon::now();

            if ($user && $now->isBefore($user->otp_expired)) {
                $user->update([
                    'otp_code' => null,
                ]);

                return response()->json(['token' => $token, 'user' => $user, 'message' => 'successfully activated your account!'], 200);
            }
            return response()->json(['error' => 'your OTP expired!'], 422);
        }catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }

    }

    public function resendOTP($id)
    {
        try{
            $user = User::find($id);

            if(!$user){
                return response()->json(['error' => 'Not Found'],404);
            }

            $first_2 = rand(11, 99);
            $second_2 = rand(11, 99);
            $otp_code = $first_2 . $second_2;

            $user->update([
                'otp_code' => $otp_code,
                'otp_expired' => Carbon::now()->addMinutes(5),
            ]);

            $this->sendOTP($user->phone, $user->otp_code);

            return response()->json(['new_otp' => $user->otp_code . ' is your new OTP, be careful this time!']);
        }catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function signin(SinginRequest $request)
    {
        try {
            if (
                Auth::attempt(['password' => $request->password, 'phone' => $request->phone, 'status' => 1])
            ) {

                $user = Auth::user();
                /** @var \App\Models\User $user */

                $user->tokens()->delete();

                $token = $user->createToken($request->password . 'ATUH_TOKEN')->plainTextToken;

                return response()->json(['token' => $token, 'user' => new UserResource($user)], 200);
            } else {
                return response()->json(['message' => 'Invalid sigin! Make sure u logout from the other device'], 401);
            }
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function forgot_password(ForgotPasswordRequest $request)
    {
        try{
            $user = User::where('phone', $request->phone)->first();

            $first_2 = rand(11, 99);
            $second_2 = rand(11, 99);
            $otp_code = $first_2 . $second_2;

            $user->update([
                'otp_code' => $otp_code,
                'otp_expired' => Carbon::now()->addMinutes(5),
            ]);

            $this->sendOTP($request->phone, $otp_code);

            return response()->json(['use this OTP {' . $user->otp_code . '} to change your password']);
        }catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function reset_password(ResetPasswordRequest $request)
    {
        try{
            if ($request->otp_code == null) {
                return response()->json(['??' => "nice try!"]);
            }

            $user = User::where('otp_code', $request->otp_code)->first();

            $now = Carbon::now();

            if ($user && $now->isBefore($user->otp_expired)) {
                $user->update([
                    'otp_code' => null,
                    'password' => $request->password,
                ]);
                return response()->json(['message' => 'successfully changed your password'], 200);
            }

            return response()->json(['error' => 'your OTP expired!'], 422);
        }catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }


    public function signout(Request $request){
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => "successfully logout."
        ],200);
    }

}
