<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Models\Classes;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Quiz;
use App\Service\QuizService;
use Illuminate\Http\Request;
use App\Http\Requests\AttachQuestionsRequest;

class QuizController extends Controller
{
    protected $quizService;
    public function __construct(QuizService $quizService)
    {
         $this->quizService = $quizService;
    }
    public function index()
    {
        if(auth()->user()->user_type() == 'admin'){
            $quizzes = Quiz::with(['class', 'subject'])->paginate(10);
        }else if(auth()->user()->user_type() == 'teacher') {
            $quizzes = Quiz::where('created_by', auth()->user()->id)
                ->with(['class', 'subject'])
                ->paginate(10);
        }

        return view('quiz.index', compact('quizzes'));
    }
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

    public function store(QuizRequest $quizRequest)
    {
        $validated = $quizRequest->validated();
        $quiz = $this->quizService->create($validated);
        return redirect()->route('quiz.selectQuestion', ['quiz' => $quiz]);
    }
    public function selectQuestion(Quiz $quiz)
    {
        $questions = Question::where('class_id', $quiz->class_id)
                            ->where('subject_id', $quiz->subject_id)
                            ->get();
        $attachedIds = $quiz->questions()->pluck('questions.id')->toArray();
        $selectedQuestionIds = old('question_ids', $attachedIds);

        return view('quiz.select_question', compact('quiz', 'questions', 'selectedQuestionIds'));
    }

    public function attachQuestions(AttachQuestionsRequest $request, Quiz $quiz)
    {
        $action = $request->input('action');
        $questionIds = $request->input('question_ids', []);

        // Attach or sync selected questions
        $quiz->questions()->sync($questionIds);

        if ($action === 'draft') {
            $quiz->update(['status' => 'in_progress']);
            return redirect()->route('quiz.index')->with('success', 'Draft saved.');
        }

        // Finalize
        $quiz->update(['status' => 'done']);
        return redirect()->route('quiz.index')->with('success', 'Quiz created successfully.');
    }
}
