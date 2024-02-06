<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StepupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'img' => $this->img,
            'first_step' => new FirstStepResource($this->whenLoaded('firstStep')),
            // 'second_step' => $this->secondStep->practice_time,
            'second_step' => $this->second_step,
            'third_step' => $this->third_step,
        ];
    }
}
