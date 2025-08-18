<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $guarded = [];

    public function quizz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');

    }

    public function  responses()
    {
        return $this->hasMany(QuizResponse::class, 'quiz_attempt_id');
    }
}
