<?php

namespace App\Http\Controllers\steps;

use App\Http\Controllers\Controller;
use App\Http\Requests\FirstStepRequest;
use App\Http\Resources\FirstStepResource;
use App\Models\FirstStep;
use App\Models\Stepup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirstStepController extends Controller
{

    public function index()
    {
        return FirstStepResource::collection(FirstStep::all());
    }

    public function store(FirstStepRequest $request)
    {

        $first_step = new FirstStep;

        $first_step->question = $request->question;
        $first_step->save();

        return new FirstStepResource($first_step);
    }

    public function update(Request $request, string $id)
    {
        $first_step = FirstStep::find($id);

        if(!$first_step){
            return response()->json(['error' => "we don't have info with that id"],404);
        }

        $used_stepUp = Stepup::where('first_step', $first_step->question)->first();
        $first_step->update([
            'question' => $request->question
        ]);

        if($used_stepUp){
            $used_stepUp->first_step = $request->question;
            $used_stepUp->save();
        }

        return new FirstStepResource($first_step);
    }

    public function destroy(string $id)
    {
        $first_step = FirstStep::find($id);

        if(!$first_step){
            return response()->json(['error' => "we don't have info with that id"],404);
        }

        $used_stepUp = Stepup::where('first_step', $first_step->question)->first();

        if($used_stepUp){
            $used_stepUp->first_step = null;
            $used_stepUp->save();
        }

        $first_step->delete();

        return new FirstStepResource($first_step);
    }
}
