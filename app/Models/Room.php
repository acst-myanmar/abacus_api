<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [ 'creater_id', 'room_name' ];

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
