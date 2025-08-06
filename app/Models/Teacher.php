<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zap\Models\Concerns\HasSchedules;

class Teacher extends Model
{
    use HasSchedules;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function subjects(){
        return $this->belongsToMany(Subject::class,'teacher_subjects','teacher_id','subject_id');
    }
}