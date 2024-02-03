<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Http\Requests\SecondStepRequest;
use App\Http\Requests\ThirdStepRequest;
use App\Http\Resources\StepupResource;
use App\Http\Resources\UserResource;
use App\Models\FirstStep;
use App\Models\Stepup;
use App\Models\ThirdStep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepupController extends Controller
{

    public function upload_img(ImageUploadRequest $request)
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $img_name = time() . '_' . $request->img->getClientOriginalName();
        $request->img->move(public_path('storage/avatars'), $img_name);

        // $user->setup_id = $user_stepup->id;
        // $user->save();

        $user_stepup->img = $img_name;
        $user_stepup->save();

        return new StepupResource($user_stepup);
    }

    public  function step_one($id) //with rs
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $user_stepup->update([
            'first_step_id' => $id,
        ]);


        $user->load('stepup');



        return new UserResource($user);

    }

    public function step_two(SecondStepRequest $request) //single request
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $user_stepup->second_step = $request->practice_time  . ":00";
        $user_stepup->save();

        $user->load('stepup');

        return new UserResource($user);
    }

    public function step_three(ThirdStepRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $ans = [];

        $data = explode(',', $request->interest_tag);

        foreach ($data as $id) {
            $third_step = ThirdStep::find($id);
            if ($third_step) {
                // echo $third_step->interest_tag;
                array_push($ans, $third_step->interest_tag);
            }
        }

        $user_stepup->third_step = $ans;
        $user_stepup->save();

        $user->load('stepup');


        return new UserResource($user);

        // return $ans;
    }

}
