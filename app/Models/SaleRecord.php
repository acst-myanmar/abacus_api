<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRecord extends Model
{
    use HasFactory;
    protected $fillable=[
        'book_id',
        'customer_id',
        'quantity',
        'price',
        'amount',
        'paid',
        'due'
    ];


    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
