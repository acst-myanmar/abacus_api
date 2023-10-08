<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleRecordResource extends JsonResource
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
            'sale_id'=>"SALE_INV_".$this->id,
            'customer_name'=>$this->customer->name??"",
            'quantity'=>$this->quantity,
            'book_id'=>$this->book_id,
            'amount'=>$this->amount,
            'paid'=>$this->paid,
            'due'=>$this->due,
            'sale_date'=>date('M/d/Y h:i:s',strtotime($this->created_at))
        ];
    }
}
