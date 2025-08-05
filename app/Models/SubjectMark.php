<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectMark extends Model
{
    protected $guarded = [];
    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function result(){
        return $this->belongsTo(Result::class);
    }
}