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

        if(!$third_step){
            return response()->json(['error' => "we don't have info with that id"],404);
        }

        $used_stepUp = Stepup::where('third_step', 'like', '%' . $third_step->interest_tag . '%' )->first(); //tags name should unique so we don't have to fu with duplicate tags!

        if($used_stepUp){
            $used_arr = explode('"', $used_stepUp->third_step); // change them into array

            $index =  array_search($third_step->interest_tag, $used_arr); //search index of updated tag
            foreach($used_arr as $item){

                if($item == $third_step->interest_tag){

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

        if(!$third_step){
            return response()->json(['error' => "we don't have info with that id"],404);
        }

        $used_stepUp = Stepup::where('third_step', 'like', '%' . $third_step->interest_tag . '%' )->first();

        if($used_stepUp){
            $used_arr = explode('"', $used_stepUp->third_step);

        $index =  array_search($third_step->interest_tag, $used_arr); //index of incoming array
        foreach($used_arr as $item){

            if($item == $third_step->interest_tag){

                $last = end($used_arr); //to remove last ]

                $last_index = array_search($last, $used_arr);  // find index of that last ]

                unset($used_arr[0], $used_arr[$index], $used_arr[$last_index]); // remove first [, deleted tag, last ]

                if($used_arr[$index +1] === ','){

                    unset($used_arr[0], $used_arr[$index], $used_arr[$index+1], $used_arr[$last_index]); // also remove , after that tag
                }
            }
        }

        $toString =  implode('', $used_arr); // change to string to remove [1] => , [2] => ....

        $back_array = explode(',', $toString); // back to array to store in json format

        $used_stepUp->third_step = $back_array;
        $used_stepUp->save();
        }

        $third_step->delete();

        return new ThirdStepResource($third_step);
    }
}
