<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Service\ScheduleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Zap\Facades\Zap;

class ScheduleController extends Controller
{
    protected $scheduleService;
    protected $totalSlot,$totalCount,$weekDays;

    public function __construct(ScheduleService $scheduleService)  {
        $this->scheduleService = $scheduleService;
        // $this->totalSlot = $totalSlot;
        $this->totalSlot = [
            (object)['name' => 'slot1', 'period' => '09:00 - 10:00'],
            (object)['name' => 'slot2', 'period' => '10:00 - 11:00'],
            (object)['name' => 'slot3', 'period' => '12:00 - 13:00'],
            (object)['name' => 'slot4', 'period' => '13:00 - 14:00'],
            (object)['name' => 'slot5', 'period' => '14:00 - 15:00'],
        ];
        $this->weekDays = [
            (object)['id' => 1, 'name' => 'Monday'],
            (object)['id' => 2, 'name' => 'Tuesday'],
            (object)['id' => 3, 'name' => 'Wednesday'],
            (object)['id' => 4, 'name' => 'Thursday'],
            (object)['id' => 5, 'name' => 'Friday'],
        ];
        $this->totalCount = count($this->totalSlot);
    }   
    
    public function index(Request $request)
{
    $user = Auth::user();
    $classes = $this->scheduleService->getClasses($user);
    
    // Use the properties defined in constructor
    $weekDays = $this->weekDays;
    $timeSlots = $this->totalSlot;
    $scheduleData = []; // This will be organized by class
    
    $teachers = Teacher::with(['user', 'appointmentSchedules'])->get();
    $subjects = Subject::all()->keyBy('id');
    
    foreach ($classes as $class) {
        // Initialize schedule data for this class
        $scheduleData[$class->id] = [];
        
        foreach ($teachers as $teacher) {
            $schedules = $teacher->appointmentSchedules
                ->where('metadata.class_id', $class->id);
            
            foreach ($schedules as $schedule) {
                $metadata = $schedule->metadata;
                
                if (!isset($metadata['slot']) || 
                    !isset($metadata['subject_id']) || 
                    !isset($metadata['day'])) {
                    continue;
                }
                
                $slot = $metadata['slot'];
                $subjectId = $metadata['subject_id'];
                $dayName = ucfirst($metadata['day']);
                
                $subject = $subjects->get($subjectId);
                if ($subject) {
                    $scheduleData[$class->id][$slot][$dayName] = [
                        'subject' => $subject->name,
                        'teacher' => $teacher->user ? $teacher->user->name : 'N/A',
                    ];
                }
            }
        }
    }
    
    return view('time_table.index', compact('classes', 'weekDays', 'timeSlots', 'scheduleData'));
}




    public function create()
    {
        $allClass = Classes::all();
        $allSubject = Subject::all();
        $allTeachers = Teacher::with('user')->get();
        $weekDays = $this->weekDays;
        $totalSlot = $this->totalSlot;

        return view('time_table.create', compact('allClass','allSubject','totalSlot','allTeachers','weekDays'));
    }

    public function store(ScheduleRequest $request){
      DB::beginTransaction();
       try {
        //code...
            $validated = $request->validated();
            $this->scheduleService->addSchedule($validated);
            DB::commit();
            return redirect()->route('schedule.index')->with('success', 'Schedule saved successfully!');

       } catch (Exception $e) {
        //throw $th;
        DB::rollBack();
        dd($e);
         return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while creating your teacher.')
                ->withInput();
       }
    }

    public function getTeachers(string $id){
        try {
            //code...
            [$start_time, $end_time] = array_map('trim', explode('-', request()->period));
            $subject = Subject::with('teachers.user')->findOrFail($id);

            $teachers = $subject->teachers;
            // dd(request()->day);
            $available_teachers  = $this->scheduleService->availableTeachers($teachers,request()->start_date,request()->end_date,$start_time,$end_time,request()->day);
            
            return response()->json([
                'teachers' => $available_teachers->values(),
                
            ]);
           } catch (Exception $e) {
            //throw $th;
                return response()->json(['error' => $e->getMessage()], 500);
        }     
    }



    
   
}