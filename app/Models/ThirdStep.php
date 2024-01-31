<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdStep extends Model
{
    use HasFactory;

    protected $fillable = ['interest_tag', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
