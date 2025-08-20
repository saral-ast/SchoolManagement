<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function create()
    {
        $classes = Classes::all();
        if(auth()->user()->user_type() == 'admin'){
            $subjects = Subject::all();
            // dd($subjects);
        }else if(auth()->user()->user_type() == 'teacher'){
            $subjects = auth()->user()->teacher->subjects;
            // dd($subjects);
        }
        // dd($subjects);
        return view('quiz.create', compact('classes', 'subjects'));
    }
}
