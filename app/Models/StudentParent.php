<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $table = 'student_parents';
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function student(){
        return $this->belongsTo( Student::class);
    }
}