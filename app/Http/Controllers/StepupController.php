<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
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
use Illuminate\Support\Facades\Storage;

class StepupController extends Controller
{

    public function uploadImg($img_url)
    {
        if($img_url === null){
            return;
        }

        $user_stepup = Stepup::where('user_id', auth()->user()->id)->first();

        $img_name = time() . '_' . $img_url->getClientOriginalName();
        Storage::disk('public')->put('profiles/' .$img_name, file_get_contents($img_url));

        $user_stepup->img = $img_name;
        $user_stepup->save();

    }

    public  function stepOne($id) //with rs
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

    public function stepTwo($practice_time) //single request
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

    public function stepThree($tag_ids)
    {
        if($tag_ids === null){
            return;
        }
        $ans = [];

        // $data = explode(',', $tag_ids);

        foreach ($tag_ids as $id) {
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

        return ApiHelper::responseWithSuccess(null,['first_steps' => $first_steps, 'second_steps' => $second_steps, 'third_steps' => $third_steps]);
    }

    public function submitedAnswers(AnswersRequest $request){

            $this->uploadImg($request->img);
            $this->stepOne($request->first_step);
            $this->stepTwo($request->second_step);
            $this->stepThree($request->third_steps);

            if($request->first_step){
                auth()->user()->firstStep;
            }

            return ApiHelper::responseWithSuccess(
                'Successfully Stepup',
                new StepupResource(auth()->user()->stepup));
    }

}
