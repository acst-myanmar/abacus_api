<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stepup extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'second_step', 'first_step_id'];

    protected $casts = [
        'third_step' => 'json',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function firstStep(){
        return $this->belongsTo(FirstStep::class);
    }
}
