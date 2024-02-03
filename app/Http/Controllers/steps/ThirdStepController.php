<?php

namespace App\Http\Controllers\steps;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThirdStepRequest;
use App\Http\Resources\ThirdStepResource;
use App\Models\Stepup;
use App\Models\ThirdStep;
use Illuminate\Http\Request;

class ThirdStepController extends Controller
{
    public function index()
    {
        return ThirdStepResource::collection(ThirdStep::all());
    }

    public function store(ThirdStepRequest $request)
    {
        $third_step = new ThirdStep;

        $third_step->interest_tag = $request->interest_tag;
        $third_step->save();

        return new ThirdStepResource($third_step);
    }

    public function update(ThirdStepRequest $request, string $id)
    {
        $third_step = ThirdStep::find($id);

        if (!$third_step) {
            return response()->json(['error' => "we don't have info with that id"], 404);
        }

        $used_stepUps = Stepup::where('third_step', 'like', '%' . $third_step->interest_tag . '%')->get();

        if ($used_stepUps) {

            foreach ($used_stepUps as $used_stepUp) {

                $used_arr = $used_stepUp->third_step;  // array

                $index =  array_search($third_step->interest_tag, $used_arr); //search index of updated tag
                foreach ($used_arr as $item) {

                    if ($item == $third_step->interest_tag) {

                        $replacements = array($index => $request->interest_tag); // to replace

                        $new = array_replace($used_arr, $replacements); //replace updated tag with old one
                    }
                }

                $used_stepUp->third_step = $new;


                $used_stepUp->save();
                // return $new;
            }
        };


        $third_step->interest_tag = $request->interest_tag;
        $third_step->save();

        return new ThirdStepResource($third_step);
    }

    public function destroy(string $id)
    {
        $third_step = ThirdStep::find($id);

        if (!$third_step) {
            return response()->json(['error' => "we don't have info with that id"], 404);
        }

        $used_stepUps = Stepup::where('third_step', 'like', '%' . $third_step->interest_tag . '%')->get();

        if ($used_stepUps) {

            foreach ($used_stepUps as $used_stepUp) {
                // print_r ($used_stepUp->third_step);

                $used_arr = $used_stepUp->third_step;
                // print_r (($used_arr));

                $index =  array_search($third_step->interest_tag, $used_arr); //index of incoming array

                foreach ($used_arr as $item) {

                    if ($item == $third_step->interest_tag) {

                        if (count($used_arr) > 1) {

                            unset($used_arr[$index]);

                            $toString =  implode(',', $used_arr);
                            $back_array = explode(',', $toString);


                            $used_stepUp->third_step = $back_array;

                            $used_stepUp->save();
                        } else {
                            $used_stepUp->third_step = null;
                            $used_stepUp->save();
                        }
                    }
                }
            }
        }
        $third_step->delete();

        return new ThirdStepResource($third_step);
    }
}
