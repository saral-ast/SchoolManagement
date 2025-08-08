<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Classes;
use App\Models\Student;
use App\Service\StudentService;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $userService,$studentService;

    public function __construct(UserService $userService,StudentService $studentService){
        $this->userService = $userService;
        $this->studentService = $studentService;
    }
    public function index()
    {
        $students = Student::whereHas('user')
                            ->whereHas('class')
                            ->with('user','class')->paginate(10);
        return view('students.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allClass = Classes::all();
        return view('students.create',compact('allClass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $user = $this->userService->create($validated);
            $this->studentService->create($validated,$user->id);

            DB::commit();
            return redirect()->route('student.index')->with('status','Student Created SuccessFully');
                
            
        }   catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while creating your teacher.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $allClass = Classes::all();   
        return view('students.edit',compact('student','allClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, Student $student)
    {
        // dd($request->all());
         DB::beginTransaction();
        try {
            
            $validated = $request->validated();
            

            $user = $student->user;
            $this->userService->update($validated,$user);
            $role = config('roles.models.role')::where('slug', '=', 'student')->first();
            // dd($role);  //choose the default role upon user creation.

            $user->attachRole($role);
            $this->studentService->update($student,$validated);

            DB::commit();
            return redirect()->route('student.index')->with('status','Student Updated SuccessFully');
                
            
        }   catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while creating your teacher.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        DB::beginTransaction();
        try {
            $user = $student->user;
            $this->studentService->destroy($student);
            $this->userService->destroy($user);
            DB::commit();
            return redirect()->route('student.index')->with('status', 'Student deleted successfully');
 
        } catch (Exception $e) {
             DB::rollBack();
            return redirect()->back()
                ->with('error', $e ?? 'An error occurred while deleting your student.');
        }
    }

    public function getStudents(string $id){
       try {
        //code...
         $students = Student::where('class_id',$id)->with('user')->get();
        return response()->json([
            'students' => $students
        ]);
       } catch (Exception $e) {
           return response()->json([
              'error' =>'Unable to fetch students' 
           ],500);
       }
    }

    public function getStudentsByMultipleClasses(Request $request)
{
    $classIds = $request->input('class_ids', []);
    
    $students = Student::whereIn('class_id', $classIds)
        ->with(['user', 'class'])
        ->orderBy('class_id')
        // ->orderBy('name')
        ->get();
    
    return response()->json([
        'students' => $students
    ]);
}

}