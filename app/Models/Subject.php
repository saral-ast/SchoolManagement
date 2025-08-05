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
}