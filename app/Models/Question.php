<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question_setting_id', 'question', 'answer', 'user_answer', 'is_correct', 'round_no', 'start_time', 'end_time'];

    public function setting(){
        return $this->belongsTo(QuestionSetting::class, 'question_setting_id');
    }
}
