<?php

namespace App\Http\Resources;

use App\Models\QuestionSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question_setting_id' => $this->question_setting_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'user_answer' => $this->user_answer,
            'is_correct' => $this->is_correct,
            'round_no' => $this->round_no,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'setting' => new QuestionSettingResource($this->whenLoaded('setting')),
        ];
    }
}
