<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;

class QuestionController extends Controller
{


    public function directMethod(QuestionRequest $request)
    {
        try {

            $validatedData = $request->validated();

            $firstNumber = [];
            $firstNumber = rand(1, 9);

            $possibleOutcome = [];
            $questions = [$firstNumber];

            for ($i = 0; $i <= $validatedData['line']; $i++) {

                switch ($firstNumber) {
                    case 0:
                        $possibleOutcome = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                        break;
                    case 1:
                        $possibleOutcome = [1, 2, 3, 5, 6, 7, 8, -1];
                        break;
                    case 2:
                        $possibleOutcome = [1, 2, 5, 6, 7, -1, -2];
                        break;
                    case 3:
                        $possibleOutcome = [1, 5, 6, -1, -2, -3];
                        break;

                    case 4:
                        $possibleOutcome = [5, -1, -2, -3, -4];
                        break;

                    case 5:
                        $possibleOutcome = [1, 2, 3, 4, -5];
                        break;

                    case 6:
                        $possibleOutcome = [1, 2, 3, -1, -5, -6];
                        break;
                    case 7:
                        $possibleOutcome = [1, 2, -1, -2, -5, -6, -7];
                        break;
                    case 8:
                        $possibleOutcome = [1, -1, -2, -3, -5, -6, -7, -8];
                        break;
                    case 9:
                        $possibleOutcome = [-1, -2, -3, -4, -5, -6, -7, -8, -9];
                        break;
                }

                $nextQuestion = $possibleOutcome[array_rand($possibleOutcome)];
                $questions[] = $nextQuestion;
                $firstNumber = array_reduce($questions, function ($a, $b) {
                    return $a + $b;
                });
            }
            $answer = array_sum($questions);

            $responseData = [
                'questions' => $questions,
                'answer' => $answer,
            ];

            return ApiResponse::responseWithSuccess('Direct Method Success!', $responseData, 200);
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
