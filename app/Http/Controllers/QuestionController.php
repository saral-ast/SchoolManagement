<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create()
    {
        $classes = Classes::all();
        $userType = auth()->user()->user_type();
        if($userType === 'admin'){
            $subjects = Subject::all();
        }elseif ($userType === 'teacher'){
            $teacher = Teacher::where('user_id', auth()->id())->first();
            $subjects = $teacher->subjects()->get();
        }
        return view('questions.create',compact('subjects', 'classes'));
    }

    public function store(QuestionRequest $questionRequest)
    {
        $validated = $questionRequest->validated();
        dd($validated);
//        $question = auth()->user()->questions()->create($validated);
    }
}
