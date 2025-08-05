<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $guarded = [];
    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function class(){
        return $this->belongsTo(Classes::class);
    }    
    
    public function subjectMarks(){
        return $this->hasMany(SubjectMark::class);
    }
}