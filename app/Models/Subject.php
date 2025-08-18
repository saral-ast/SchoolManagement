<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];
    public function teachers(){
        return $this->belongsToMany(Teacher::class,'teacher_subjects','subject_id','teacher_id');
    }

    public function marks(){
        return $this->hasMany(SubjectMark::class);
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class, 'subject_id');
    }
    public function questions(){
        return $this->hasMany(Question::class, 'subject_id');
    }
}
