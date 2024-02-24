<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSetting extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'row', 'digit', 'speed', 'round', 'completed', 'exam', 'exam_time', 'exam_name'];
    //level and show_type for enum left

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }
}
