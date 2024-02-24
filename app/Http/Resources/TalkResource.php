<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TalkResource extends JsonResource
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
            'message' => $this->message,
            'sender' => $this->sender->username,
            'sender_id' => $this->sender->id,
            'room_id' => $this->room_id,
            // 'sender' => new UserResource($this->whenLoaded('sender')),
        ];
    }
}
