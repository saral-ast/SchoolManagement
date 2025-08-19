<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Classes;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Teacher;
use App\Service\QuestionService;
use Illuminate\Http\Request;

class QuestionController
{
    protected $questionService;
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index(){
        try {
            $userType = auth()->user()->user_type();
            if($userType === 'admin'){
                $questions = Question::with('subject','class')->paginate(10);
            } elseif ($userType === 'teacher') {
                $teacher = Teacher::where('user_id', auth()->id())->first();
                $subjects = $teacher->subjects->pluck('id')->toArray();
                $questions = Question::whereIn('subject_id', $subjects)
                    ->with('subject', 'class')
                    ->paginate(10);
            }
            return view('questions.index', compact('questions'));
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Failed to load questions: ' . $exception->getMessage());
        }
    }

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
        try {
            $validated = $questionRequest->validated();
            $this->questionService->createQuestion($validated);
            return redirect()->route('question.index')->with('status', 'Question Created Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Failed to create question: ' . $exception->getMessage());
        }
    }
    public function edit(Question $question)
    {
        try {
            return view('questions.edit', compact('question'));
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Failed to load question for editing: ' . $exception->getMessage());
        }
    }

    public function update(QuestionRequest $questionRequest, Question $question){
        try {
            $validated = $questionRequest->validated();
            $this->questionService->updateQuestion($validated, $question);
            return redirect()->route('question.index')->with('status', 'Question Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', 'Failed to update question: ' . $exception->getMessage());
        }
    }
}
