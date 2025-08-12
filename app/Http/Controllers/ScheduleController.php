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
use Zap\Models\Schedule;

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

      
        $weekStart = Carbon::now()->setISODate($isoYear, $isoWeek, 1); 
        $weekEnd = $weekStart->copy()->addDays(4); 
 
        $classes = $this->scheduleService->getClasses($user);
        
        $weekDays = $this->weekDays; 
        $timeSlots = $this->totalSlot;
        $scheduleData = [];
        
        if ($classes->count() > 0) {
            // Use service method to get schedules
            $scheduleData = $this->scheduleService->getSchedulesForWeek($classes, $weekStart, $weekEnd);
        }
        $weekDaysWithDates = $this->scheduleService->generateWeekDaysWithDates($weekStart);

        return view('time_table.index', compact(
            'classes', 
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

    public function edit(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type() !== 'admin') {
            return redirect()->route('schedule.index')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'schedule_id' => 'required|integer',
            'date' => 'nullable|date',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $metadata = $schedule->metadata;

        $class = Classes::findOrFail($metadata['class_id']);
        $subject = Subject::findOrFail($metadata['subject_id']);

        $overall = Schedule::where('metadata->class_id', $metadata['class_id'])
            ->where('metadata->subject_id', $metadata['subject_id'])
            ->where('metadata->slot', $metadata['slot'])
            ->where('metadata->day', $metadata['day'])
            ->orderBy('start_date')
            ->get(['start_date','end_date']);

        return view('time_table.edit', [
            'class' => $class,
            'subject' => $subject,
            'schedule' => $schedule,
            'metadata' => $metadata,
            'overallStartDate' => optional($overall->min('start_date'))->format('Y-m-d'),
            'overallEndDate' => optional($overall->max('end_date'))->format('Y-m-d'),
            'clickedDate' => $validated['date'] ?? null,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type() !== 'admin') {
            return redirect()->route('schedule.index')->with('error', 'Unauthorized');
        }

        $data = $request->validate([
            'schedule_id' => 'required|integer',
            'proxy_teacher_id' => 'required|integer|exists:teachers,id',
            'date' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($data['schedule_id']);
        $metadata = $schedule->metadata;
        $weekday = strtolower($metadata['day']);

        $selectedDate = Carbon::parse($data['date']);
        if (strtolower($selectedDate->format('l')) !== $weekday) {
            return back()->withInput()->with('error', "Selected date must be a {$weekday}.");
        }

        if ((int)$data['proxy_teacher_id'] === (int)$schedule->schedulable_id) {
            return back()->withInput()->with('error', 'Proxy teacher cannot be the same as the original teacher.');
        }

        $proxyTeacher = Teacher::findOrFail($data['proxy_teacher_id']);
        if (!$proxyTeacher->subjects()->where('subjects.id', $metadata['subject_id'])->exists()) {
            return back()->withInput()->with('error', 'Selected proxy teacher cannot teach this subject.');
        }
        // dd($data);
        DB::beginTransaction();
        try {
            $this->scheduleService->updateSchedule(
                 $data['schedule_id'],
                 $data['date'],
                 $data['proxy_teacher_id'],
                // $this->totalSlot
            );
            DB::commit();
            return redirect()->route('schedule.index')->with('success', 'Schedule updated for the selected day.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage() ?: 'Failed to update schedule.');
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

    public function teacherSchedule(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type() !== 'teacher') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $teacher = $user->teacher()->first();
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Teacher profile not found.');
        }

        $weekStart = $request->get('week_start', now()->startOfWeek());
        if (is_string($weekStart)) {
            $weekStart = Carbon::parse($weekStart);
        }
        $weekEnd = $weekStart->copy()->endOfWeek();

       
        $scheduleData = $this->scheduleService->getTeacherSchedulesForWeek($teacher, $weekStart, $weekEnd);

        $weekDaysWithDates = $this->scheduleService->generateWeekDaysWithDates($weekStart);

        // Get time slots
        $timeSlots = $this->totalSlot;

        return view('time_table.teacher_schedule', compact(
            'scheduleData',
            'weekDaysWithDates',
            'timeSlots',
            'weekStart',
            'weekEnd',
            'teacher'
        ));
    }

    


   
}