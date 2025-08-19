<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];


    public function casts(): array
    {
        return [
            'options' => 'array',
            'mark' => 'integer',
        ];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions', 'question_id', 'quiz_id');
    }

    public function responses()
    {
        return $this->hasMany(QuizResponse::class,    'question_id');
    }
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
