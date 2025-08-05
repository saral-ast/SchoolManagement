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
    
    public function parent(){
        return $this->belongsTo(StudentParent::class);
    }

    public function results(){
        return $this->hasMany(Result::class);
    }
    
}