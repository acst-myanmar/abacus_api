<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswersRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Requests\SecondStepRequest;
use App\Http\Requests\ThirdStepRequest;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\FirstStepResource;
use App\Http\Resources\SecondStepReource;
use App\Http\Resources\StepupResource;
use App\Http\Resources\ThirdStepResource;
use App\Http\Resources\UserResource;
use App\Models\FirstStep;
use App\Models\SecondStep;
use App\Models\Stepup;
use App\Models\ThirdStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepupController extends Controller
{

    // public function upload_img(ImageUploadRequest $request)
    public function upload_img($img_url)
    {
        if($img_url === null){
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $img_name = time() . '_' . $img_url->getClientOriginalName();
        $img_url->move(public_path('storage/avatars'), $img_name);

        // $user->setup_id = $user_stepup->id;
        // $user->save();

        $user_stepup->img = $img_name;
        $user_stepup->save();

        return new StepupResource($user_stepup);
    }

    public  function step_one($id) //with rs
    {

        if($id === null){
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->stepup->update([
                'first_step_id' => $id,
            ]);

        $user->stepup->load('firstStep');

        return new StepupResource($user->stepup);


        // $user->load('stepup');

        // return new UserResource($user);

    }

    // public function step_two(SecondStepRequest $request) //single request
    public function step_two($practice_time) //single request
    {
        if($practice_time === null){
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $user_stepup->second_step = $practice_time  . ":00";
        $user_stepup->save();

        $user_stepup->load('firstStep');

        return new StepupResource($user_stepup);

    }

    // public function step_three(ThirdStepRequest $request)
    public function step_three($tagIds)
    {
        if($tagIds === null){
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user_stepup = Stepup::where('user_id', $user->id)->first();

        $ans = [];

        $data = explode(',', $tagIds);

        foreach ($data as $id) {
            $third_step = ThirdStep::find($id);
            if ($third_step) {
                // echo $third_step->interest_tag;
                array_push($ans, $third_step->interest_tag);
            }
        }

        $user_stepup->third_step = $ans;
        $user_stepup->save();

        $user_stepup->load('firstStep');

        return new StepupResource($user_stepup);

    }

    public function resources(){

        $first_steps = FirstStepResource::collection(FirstStep::all());

        $second_steps = SecondStepReource::collection(SecondStep::all());

        $third_steps = ThirdStepResource::collection(ThirdStep::all());

        return response()->json(['resources' => ['first_steps' => $first_steps, 'second_steps' => $second_steps, 'third_steps' => $third_steps]]);
    }

    public function submited_answers(AnswersRequest $request){


        // // $request->user()->tokens()->delete();
         /** @var \App\Models\User $user */
         $user = Auth::user();


            $this->upload_img($request->img);
            $this->step_one($request->first_step);
            $this->step_two($request->second_step);
            $this->step_three($request->third_steps);

            if($request->first_step){
                $user->stepup->load('firstStep');
            }

            return new StepupResource($user->stepup);



    }

}
