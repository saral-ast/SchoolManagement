<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function class(){
        return $this->belongsTo(Classes::class);
    }

    public function parents(){
        return $this->belongsToMany(StudentParent::class, 'student_parent_pivots', 'student_id', 'parent_id');
    }

    public function results(){
        return $this->hasMany(Result::class);
    }

    public function attempts(){
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

}
