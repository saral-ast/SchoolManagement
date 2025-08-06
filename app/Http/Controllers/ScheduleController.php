<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Service\ScheduleService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)  {
        $this->scheduleService = $scheduleService;
    }
    public function create()
    {
        $allClass = Classes::all();
        $allSubject = Subject::all();
        $allTeachers = Teacher::with('user')->get();

        $totalSlot = [
            (object)['name' => 'slot1', 'period' => '09:00 - 10:00'],
            (object)['name' => 'slot2', 'period' => '10:00 - 11:00'],
            (object)['name' => 'slot3', 'period' => '12:00 - 13:00'],
            (object)['name' => 'slot4', 'period' => '13:00 - 14:00'],
            (object)['name' => 'slot5', 'period' => '14:00 - 15:00'],
        ];

        return view('time_table.create', compact('allClass','allSubject','totalSlot','allTeachers'));
    }

    public function store(ScheduleRequest $request){
        $validated = $request->validated();
        dd($validated);
    }

    public function getTeachers(string $id){
           try {
            //code...
             [$start_time, $end_time] = array_map('trim', explode('-', request()->period));
            $subject = Subject::with('teachers.user')->findOrFail($id);

            $teachers = $subject->teachers;
            // dd($teachers);
            $available_teachers  = $this->scheduleService->availableTeachers($teachers,request()->start_date,request()->end_date,$start_time,$end_time);
            
            return response()->json([
                'teachers' => $available_teachers->values(),
                
            ]);
           } catch (Exception $e) {
            //throw $th;
                    return response()->json(['error' => $e->getMessage()], 500);

           }

        
    }
}