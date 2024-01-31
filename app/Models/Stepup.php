<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stepup extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'first_step', 'second_step', 'third_step'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
