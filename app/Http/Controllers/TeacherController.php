<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Service\TeacherService;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $userService,$teacherService;

    public function __construct(UserService $userService,TeacherService $teacherService){
        $this->userService = $userService;
        $this->teacherService = $teacherService;
    }
    public function index()
    {
        $teachers = Teacher::whereHas('user')
                            ->whereHas('subjects')
                            ->with('user','subjects')->paginate(10);
        return view('teachers.index',compact('teachers'));
    }


    public function show(Teacher $teacher)
    {
        return view('teachers.show',compact('teacher'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('teachers.create',compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $user = $this->userService->create($validated);
            $role = config('roles.models.role')::where('slug', '=', 'teacher')->first();
            $user->attachRole($role);
            $this->teacherService->create($validated,$user->id);
            DB::commit();
            return redirect()->route('teacher.index')->with('status','Teacher Created SuccessFully');


        }   catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while creating your teacher.')
                ->withInput();
        }

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $subjects = Subject::all();
        return view('teachers.edit',compact('teacher','subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $user = $teacher->user;
            $this->userService->update($validated, $user);
            $this->teacherService->update($validated,$teacher);

            DB::commit();
            return redirect()->route('teacher.index')->with('status', 'Teacher Updated Successfully');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while updating the teacher.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();
        try {
            $user = $teacher->user;
            $this->teacherService->destroy($teacher);
            $this->userService->destroy($user);
            DB::commit();
            return redirect()->route('teacher.index')->with('status', 'Teacher deleted successfully');

        } catch (Exception $e) {
             DB::rollBack();
            return redirect()->back()
                ->with('error', $e ?? 'An error occurred while creating your admin.');
        }
    }
}
