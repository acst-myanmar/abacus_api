<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'row' => $this->row,
            'digit' => $this->digit,
            'speed' => $this->speed,
            'round' => $this->round,
            'completed' => $this->completed,
            'exam' => $this->exam,
            'exam_time' => $this->exam_time,
            'exam_name' => $this->exam_name,
            'user' => new UserResource($this->whenLoaded('user')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
