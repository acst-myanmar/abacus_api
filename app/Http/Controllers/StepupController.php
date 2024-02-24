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

        $user_stepup = Stepup::where('user_id', auth()->user()->id)->first();

        $img_name = time() . '_' . $img_url->getClientOriginalName();
        $img_url->move(public_path('storage/avatars'), $img_name);

        $user_stepup->img = $img_name;
        $user_stepup->save();

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
    }

    // public function step_two(SecondStepRequest $request) //single request
    public function step_two($practice_time) //single request
    {
        if($practice_time === null){
            return;
        }

         /** @var \App\Models\User $user */
         $user = Auth::user();
         $user->stepup->update([
            'second_step' =>  $practice_time  . ":00",
        ]);


    }

    // public function step_three(ThirdStepRequest $request)
    public function step_three($tagIds)
    {
        if($tagIds === null){
            return;
        }
        $ans = [];

        // $data = explode(',', $tagIds);

        foreach ($tagIds as $id) {
            $third_step = ThirdStep::find($id);
            if ($third_step) {
                array_push($ans, $third_step->interest_tag);
            }
        }

         /** @var \App\Models\User $user */
         $user = Auth::user();
         $user->stepup->update([
            'third_step' => $ans,
        ]);

    }

    public function resources(){

        $first_steps = FirstStepResource::collection(FirstStep::all());

        $second_steps = SecondStepReource::collection(SecondStep::all());

        $third_steps = ThirdStepResource::collection(ThirdStep::all());

        return response()->json(['resources' => ['first_steps' => $first_steps, 'second_steps' => $second_steps, 'third_steps' => $third_steps]]);
    }

    public function submited_answers(AnswersRequest $request){

            $this->upload_img($request->img);
            $this->step_one($request->first_step);
            $this->step_two($request->second_step);
            $this->step_three($request->third_steps);

            return new StepupResource(auth()->user()->stepup);
    }

}
