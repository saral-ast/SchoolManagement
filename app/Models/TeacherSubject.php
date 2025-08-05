<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $guarded = [];
    public function teachers(){
        return $this->belongsToMany(Teacher::class);
    }
    
    public function subjects(){
        return $this->belongsToMany(Subject::class);
    }
}