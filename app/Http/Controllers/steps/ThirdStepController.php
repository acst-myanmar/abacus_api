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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ThirdStepResource::collection(ThirdStep::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ThirdStepRequest $request)
    {
        $third_step = new ThirdStep;

        $third_step->interest_tag = $request->interest_tag;
        $third_step->save();

        return new ThirdStepResource($third_step);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ThirdStepRequest $request, string $id)
    {
        $third_step = ThirdStep::find($id);

        if (!$third_step) {
            return response()->json(['error' => "we don't have info with that id"], 404);
        }

        $used_stepUps = Stepup::where('third_step', 'like', '%' . $third_step->interest_tag . '%')->get();


        if ($used_stepUps) {

            foreach ($used_stepUps as $used_stepUp) {
                // print_r ($used_stepUp->third_step);

                $used_arr = explode('"', $used_stepUp->third_step); // change them into array

                $index =  array_search($third_step->interest_tag, $used_arr); //search index of updated tag
                foreach ($used_arr as $item) {

                    if ($item == $third_step->interest_tag) {

                        $replacements = array($index => $request->interest_tag); // to replace

                        $new = array_replace($used_arr, $replacements); //replace updated tag with old one
                    }
                }
                $last = end($new); //to remove last ]

                $last_index = array_search($last, $new);  // find index of that last ]

                unset($new[0], $new[$last_index]); // kill fist [ && last ]
                // print_r ($new);

                $toString =  implode('', $new);  // to remove those akuma

                $back_array = explode(',', $toString);  // back to array


                $used_stepUp->third_step = $back_array;
                $used_stepUp->save();
            }
        };


        $third_step->interest_tag = $request->interest_tag;
        $third_step->save();

        return new ThirdStepResource($third_step);
    }

    /**
     * Remove the specified resource from storage.
     */
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

                $used_arr = explode('"', $used_stepUp->third_step);
                // print_r (($used_arr));

                $index =  array_search($third_step->interest_tag, $used_arr); //index of incoming array

                foreach ($used_arr as $item) {

                    if ($item == $third_step->interest_tag) {

                        $last = end($used_arr); //to remove last ]

                        $last_index = array_search($last, $used_arr);  // find index of that last ]


                        if (count($used_arr) > 3) {

                            if ($used_arr[$index - 1] === ',' && $used_arr[$index + 1] === ']') { //  remove , before that tag if that's last tag

                                unset($used_arr[0], $used_arr[$index], $used_arr[$index - 1], $used_arr[$last_index]);
                                print_r($used_arr);
                            } else if ($used_arr[$index + 1] === ',') { // also remove , after that tag if that's inner tag

                                unset($used_arr[0], $used_arr[$index], $used_arr[$index + 1], $used_arr[$last_index]);
                            }

                            $toString =  implode('', $used_arr);
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
