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

    public function students(){
        return $this->belongsToMany(Student::class, 'student_parent_pivots', 'parent_id', 'student_id');
    }
}
