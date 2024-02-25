<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Requests\QuestionSettingRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuestionSettingResource;
use App\Models\Question;
use App\Models\QuestionSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionSettingController extends Controller
{
    public function questionSetting($id)
    {
        try {
            $setting = QuestionSetting::find($id);
            $setting->questions;
            $setting->user;
            return ApiHelper::responseWithSuccess(null, new QuestionSettingResource($setting));
        } catch (Exception $e) {
            return ApiHelper::responseWithNotFound($e->getMessage());
        }
    }

    public function setQuestionSetting(QuestionSettingRequest $request)
    {

        try {
            $setting = new QuestionSetting;
            $setting->user_id = auth()->user()->id;
            $setting->row = $request->row;
            $setting->digit = $request->digit;
            $setting->speed = $request->speed;
            $setting->round = $request->round;
            $setting->completed = $request->completed;

            $setting->exam = $request->exam;
            $setting->exam_time = $request->exam_time;
            $setting->exam_name = $request->exam_name;
            $setting->save();

            $setting->user;
            return ApiHelper::responseWithSuccess('sucessfully stepup settings', new QuestionSettingResource($setting));
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function setQuestion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'question_setting_id' => 'required|exists:question_settings,id',
            'question' => 'required',
            'answer' => 'required',
            'user_answer' => 'required',
            'round_no' => 'required',
            'end_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        try {
            $question = new Question;
            $question->question_setting_id = $request->question_setting_id;
            $question->question = $request->question;
            $question->answer = $request->answer;
            $question->user_answer = $request->user_answer;
            $question->is_correct = $request->answer === $request->user_answer;
            $question->round_no = $request->round_no;
            $question->start_time = Carbon::now()->format('H:i');
            $question->end_time = $request->end_time;
            $question->save();

            $question->setting;
            return ApiHelper::responseWithSuccess('questions are ready', new QuestionResource($question));
        } catch (Exception $e) {
            return ApiHelper::responseWithNotFound($e->getMessage());
        }
    }
}
