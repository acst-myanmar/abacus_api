<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required",
                "password" => "required"
            ], [], []);

            if ($validator->fails()) {
                return ApiResponse::responseWithBadRequest($validator->errors());
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || ($user && !Hash::check($request->password, $user->password))) {
                return ApiResponse::responseWithBadRequest("Incorrect email or password");
            }

            $token = $user->createToken('book_management');
            
            return ApiResponse::responseWithSuccess('login user', new UserResource($user), $token->plainTextToken);
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
