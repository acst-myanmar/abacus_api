<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'date'=>$this->blog_date,
            'title'=>$this->title,
            'category'=>$this->category,
            'author'=>$this->author,
            'content'=>$this->content,
            'images'=>ImageResource::collection($this->whenLoaded('images'))
        ];
    }
}
