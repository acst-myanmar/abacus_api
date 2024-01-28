<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;

class QuestionController extends Controller
{


    public function generatedirectMethod(QuestionRequest $request)
    {
        $validatedData = $request->validated();


        $firstNumber = rand(1, 9);
        $questions = [$firstNumber];

        try {

            for ($i = 1; ($i < $validatedData['line'] - 1); $i++) {
                $nextQuestion = $this->directMethod($firstNumber);

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


    public function generateLittleFriend(QuestionRequest $request)
    {
        $validatedData = $request->validated();


        $firstNumber = rand(0, 9);
        $questions = [$firstNumber];

        try {

            for ($i = 0; ($i < $validatedData['line'] - 1); $i++) {

                $firstNumber = array_reduce($questions, function ($a, $b) {
                    return $a + $b;
                });

                if ($firstNumber == 9 || $firstNumber == 0) {

                    $nextQuestion = $this->directMethod($firstNumber);
                    array_push($questions, $nextQuestion);
                } else {
                    $nextQuestion = $this->littleFriend($firstNumber);

                    array_push($questions, $nextQuestion);
                }
            }

            $answer = array_sum($questions);


            $responseData = [
                'questions' => $questions,
                'answer' => $answer,
            ];


            return ApiResponse::responseWithSuccess('LittleFriend  Method Success!', $responseData, 200);
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    public function directMethod($firstNumber)
    {
        try {

            $possibleOutcome = [];

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


            return $nextQuestion;

            // $questions[] = $nextQuestion;

            // $firstNumber = array_reduce($questions, function ($a, $b) {
            //     return $a + $b;
            // });


        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }

    public function littleFriend($firstNumber)
    {

        $possibleOutcome = [];
        switch ($firstNumber) {
            case 1:
                $possibleOutcome  = [4];
                break;
            case 2:
                $possibleOutcome  = [4, 3];
                break;
            case 3:
                $possibleOutcome  = [4, 3, 2];
                break;
            case 4:
                $possibleOutcome  = [4, 3, 2, 1];
                break;
            case 5:
                $possibleOutcome  = [-4, -3, -2, -1];
                break;
            case 6:
                $possibleOutcome  = [-4, -3, -2];
                break;
            case 7:
                $possibleOutcome = [-4, -3];
                break;
            case 8:
                $possibleOutcome  = [-4];
                break;
        }

        $nextQuestion = $possibleOutcome[array_rand($possibleOutcome)];
        return $nextQuestion;
    }


    public function getPlus($num)
    {
        $remainder = $num % 10;
        $result = [];
        switch ($remainder) {
            case 1:
                $result = [9];
                break;
            case 2:
                $result = [9, 8];
                break;
            case 3:
                $result = [9, 8, 7];
                break;
            case 4:
                $result = [9, 8, 7, 6];
                break;
            case 5:
                $result = [5];
                break;
            case 6:
                $result = [9, 5, 4];
                break;
            case 7:
                $result = [9, 8, 5, 4, 3];
                break;
            case 8:
                $result = [9, 8, 7, 5, 4, 3, 2];
                break;
            case 9:
                $result = [9, 8, 7, 6, 5, 4, 3, 2, 1];
                break;
            case 0:
                $result = [9, 8, 7, 6, 5, 4, 3, 2, 1];
                break;
        }
        $result = $result[array_rand($result)];
        return $result;
    }

    public function getMinus($num)
    {
        $remainder = $num % 10;
        $result = [];
        switch ($remainder) {
            case 1:
                $result = $this->randomNumber([-9, -8, -7, -5, -4, -3, -2]);
                break;
            case 2:
                $result = $this->randomNumber([-9, -8, -5, -4, -3]);
                break;
            case 3:
                $result = $this->randomNumber([-9, -5, -4]);
                break;
            case 4:
                $result = -5;
                break;
            case 5:
                $result = $this->randomNumber([-9, -8, -7, -6]);
                break;
            case 6:
                $result = $this->randomNumber([-9, -8, -7]);
                break;
            case 7:
                $result = $this->randomNumber([-9, -8]);
                break;
            case 8:
                $result = -9;
                break;
            case 9:
                $result = rand(-1, -9);

                break;
            case 0:
                if ($num >= 10) {
                    $result = rand(-1, -9);
                } else {
                    $result = rand(1, 9);
                }
                break;
        }


        return $result;
    }



    public function randomNumber($input_array)
    {
        $nextQuestion = $input_array[array_rand($input_array)];
        return $nextQuestion;
    }

    public function bigFriend($num)
    {


        $result = 0;
        if (($num >= 40 && $num < 50) || ($num >= 90 && $num < 99)) {
            $result = $this->getMinus($num);
        } else {
            if ($num % 10 == 9) {
                $result = $this->getPlus($num);
            } else {
                if ($this->randomNumber(['+', '-']) == "+") {
                    $result = $this->getPlus($num);
                } else {
                    if ($num > 10) {
                        $result = $this->getMinus($num);
                    } else {
                        $result = $this->getPlus($num);
                    }
                }
            }
        }

        return $result;
    }

    public function generateBigFriend(QuestionRequest $request)
    {
        $validatedData = $request->validated();


        $firstNumber = rand(1, 99);

        $questions = [$firstNumber];

        try {

            for ($i = 1; ($i < $validatedData['line']); $i++) {

                $nextQuestion = $this->bigFriend($firstNumber);

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

            return ApiResponse::responseWithSuccess('BigFriend  Method Success!', $responseData, 200);
        } catch (Exception $e) {
            return ApiResponse::responseWithBadRequest($e->getMessage());
        }
    }
}
