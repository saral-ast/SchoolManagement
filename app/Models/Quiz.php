<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];
    public  function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public  function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function  questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions','quiz_id', 'question_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
