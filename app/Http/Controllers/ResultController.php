<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Models\Classes;
use App\Models\Result;
use App\Models\Subject;
use App\Service\ResultService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $resultService;
    public function __construct(ResultService $resultService){
        $this->resultService = $resultService;
    }
    public function index()
    {
        $results = Result::whereHas('class')
                        ->whereHas('student')
                        ->with('class','student')->paginate(10);
        // dd($results);
        return view('result.index',compact('results'));                   
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allClass = Classes::all();
        $allSubject = Subject::all();
        return view('result.create',compact('allClass','allSubject'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResultRequest $request)
    {
       DB::beginTransaction(); 
        try {
           $validated = $request->validated();
          
           $result =  $this->resultService->create($validated);
           $subjectMarks = $validated['marks'];
           
           $this->resultService->addSubjectMarks($subjectMarks,$validated['student_id'],$validated['exam_type'],$result->id);
           DB::commit();
           return redirect()->back()->with('status','Result Created Successfully');
        } catch (Exception $e) {
             DB::rollBack();
             dd($e);
            return redirect()->route('result.index')
            ->with('error', $e->getMessage() ?? 'An error occurred while creating a result.')
            ->withInput();
            
        }
        
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        // dd($result);
        return view('result.show',compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        $allClass = Classes::all();
        $allSubject = Subject::all();
        return view('result.edit',compact('result','allClass','allSubject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResultRequest $request, Result $result)
    {
         DB::beginTransaction(); 
        try {
            //code...
           $validated = $request->validated();
        //    dd($validated);
           $this->resultService->update($validated,$result);
           $this->resultService->updateSubjectMarks($validated['marks'],$validated['exam_type'],$result->id);
             DB::commit();
           return redirect()->route('result.index')->with('status','Result Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
             dd($e);
            return redirect()->back()
            ->with('error', $e->getMessage() ?? 'An error occurred while creating a result.')
            ->withInput();
        }
    }

    public function downloadPdf(Result $result){
        try {
            $subjectMarks = $result->subjectMarks();
            $pdf = Pdf::loadView('result.pdf',[
                'result' => $result,
                'subjectMarks' => $subjectMarks,
            ]);
            $studernt = $result->student->user;
            $resultName = $studernt->name .'_'. $result->class->slug .'_' . $result->id;
            return $pdf->download($resultName .'.pdf');
        } catch (Exception $e) {
            return redirect()->back()
            ->with('error', $e->getMessage() ?? 'An error occurred while downloading.')
            ->withInput();
        }
    }
    
}