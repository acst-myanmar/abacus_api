<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    use HasFactory;

    public $fillable = ['room_id', 'sender_id', 'message'];

    public function sender(){
        return $this->belongsTo(User::class);
    }
}
