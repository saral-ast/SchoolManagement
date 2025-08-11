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
        
        // Get week parameter - expecting format like "2025-W06" (HTML input type="week")
        $weekParam = $request->get('week');

        // Parse ISO week-year safely and fall back to current ISO week if invalid/missing
        if (is_string($weekParam) && preg_match('/^(?<year>\d{4})-W(?<week>\d{2})$/', $weekParam, $matches) === 1) {
            $isoYear = (int) $matches['year'];
            $isoWeek = (int) $matches['week'];
            $selectedWeek = sprintf('%04d-W%02d', $isoYear, $isoWeek);
        } else {
            $now = now();
            $isoYear = (int) $now->format('o');
            $isoWeek = (int) $now->format('W');
            $selectedWeek = sprintf('%04d-W%02d', $isoYear, $isoWeek);
        }

        // Compute Monday to Friday for the selected ISO week
        $weekStart = Carbon::now()->setISODate($isoYear, $isoWeek, 1); // Monday
        $weekEnd = $weekStart->copy()->addDays(4); // Friday (Monday + 4 days)
        
        // Get classes with eager loaded schedule data
        $classes = $this->scheduleService->getClasses($user);
        
        $weekDays = $this->weekDays; // Your existing Monday-Friday array
        $timeSlots = $this->totalSlot;
        $scheduleData = [];
        
        if ($classes->count() > 0) {
            $classIds = $classes->pluck('id')->toArray();
            
            // Filter schedules that are active during the selected week (Mon-Fri)
            $teachers = Teacher::with(['user', 'appointmentSchedules' => function ($query) use ($classIds, $weekStart, $weekEnd) {
                $query->whereIn('metadata->class_id', $classIds)
                    ->whereNotNull('metadata->slot')
                    ->whereNotNull('metadata->subject_id')
                    ->whereNotNull('metadata->day')
                    // Schedule should be active during the selected week
                    ->where(function ($q) use ($weekStart, $weekEnd) {
                        $q->whereDate('start_date', '<=', $weekEnd)
                            ->whereDate('end_date', '>=', $weekStart);
                    });
            }])->whereHas('appointmentSchedules', function ($query) use ($classIds, $weekStart, $weekEnd) {
                $query->whereIn('metadata->class_id', $classIds)
                    ->whereDate('start_date', '<=', $weekEnd)
                    ->whereDate('end_date', '>=', $weekStart);
            })->get();
            
            // Pre-load all subjects to avoid N+1 queries
            $subjectIds = $teachers->flatMap(function ($teacher) {
                return $teacher->appointmentSchedules->pluck('metadata.subject_id');
            })->filter()->unique();
            
            $subjects = Subject::whereIn('id', $subjectIds)->pluck('name', 'id');
            
            // Build schedule data efficiently
            foreach ($teachers as $teacher) {
                foreach ($teacher->appointmentSchedules as $schedule) {
                    $metadata = $schedule->metadata;
                    $classId = $metadata['class_id'];
                    $subjectId = $metadata['subject_id'];
                    
                    if (isset($subjects[$subjectId])) {
                        $scheduleData[$classId][] = [
                            'class_id' => $classId,
                            'slot' => $metadata['slot'],
                            'day' => ucfirst($metadata['day']),
                            'subject' => $subjects[$subjectId],
                            'teacher' => $teacher->user ? $teacher->user->name : 'N/A',
                            'start_date' => $schedule->start_date,
                            'end_date' => $schedule->end_date,
                        ];
                    }
                }
            }
        }

        // Generate week days with actual dates
        $weekDaysWithDates = collect($weekDays)->map(function ($day, $index) use ($weekStart) {
            $dayDate = $weekStart->copy()->addDays($index);
            return (object) [
                'id' => $day->id,
                'name' => $day->name,
                'date' => $dayDate,
                'formatted_date' => $dayDate->format('M j'),
                'is_today' => $dayDate->isToday(),
            ];
        });

        return view('time_table.index', compact(
            'classes', 
            'weekDays', 
            'weekDaysWithDates',
            'timeSlots', 
            'scheduleData', 
            'selectedWeek',
            'weekStart',
            'weekEnd'
        ));
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