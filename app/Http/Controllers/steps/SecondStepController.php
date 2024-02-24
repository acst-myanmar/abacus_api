<?php

namespace App\Http\Controllers\steps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SecondStepRequest;
use App\Http\Resources\SecondStepReource;
use App\Models\SecondStep;
use App\Models\Stepup;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SecondStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SecondStepReource::collection(SecondStep::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SecondStepRequest $request)
    {
        $second_step = new SecondStep;
        $second_step->practice_time = $request->practice_time .":00";
        // $second_step->practice_time = Carbon::createFromFormat('H:i', $request->practice_time)->format('H:i');
        $second_step->save();

        return new SecondStepReource($second_step);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(SecondStepRequest $request, string $id)
    {
        $second_step = SecondStep::find($id);

        if (!$second_step) {
            return response()->json(['error' => "we don't have info with that id"], 404);
        }

        $used_stepUps = Stepup::where('second_step', $second_step->practice_time)->get();

        if ($used_stepUps) {
            foreach ($used_stepUps as $used_stepUp) {
                // print_r($used_stepUp->second_step);
                $used_stepUp->second_step = $request->practice_time . ':00';
                $used_stepUp->save();
            }
        }

        $second_step->practice_time = $request->practice_time . ':00';
        $second_step->save();

        return new SecondStepReource($second_step);
    }

    public function destroy(string $id)
    {
        $second_step = SecondStep::find($id);

        if (!$second_step) {
            return response()->json(['error' => "we don't have info with that id"], 404);
        }

        $used_stepUps = Stepup::where('second_step', $second_step->practice_time)->get();


        if ($used_stepUps) {
            foreach ($used_stepUps as $used_stepUp) {

                $used_stepUp->second_step = null;
                $used_stepUp->save();
            }
        }

        $second_step->delete();


        return new SecondStepReource($second_step);
    }
}
