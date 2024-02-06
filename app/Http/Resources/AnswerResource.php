<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'first_step' => $this->first_step,
            'second_step' => $this->second_step,
            'third_step' => $this->thrid_step,
        ];
    }
}
