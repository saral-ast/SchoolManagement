<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function create()
    {
        $allClass = Classes::all();
        $allSubject = Subject::all();
        $allTeachers = Teacher::with('user')->get();

        $totalSlot = [
            (object)['name' => 'slot1', 'period' => '9:00 - 10:00'],
            (object)['name' => 'slot2', 'period' => '10:00 - 11:00'],
            (object)['name' => 'slot3', 'period' => '11:00 - 12:00'],
            (object)['name' => 'slot4', 'period' => '13:00 - 14:00'],
            (object)['name' => 'slot5', 'period' => '14:00 - 15:00'],
        ];

        return view('time_table.create', compact('allClass','allSubject','totalSlot','allTeachers'));
    }

    public function store(ScheduleRequest $request){
        $validated = $request->validated();
        dd($validated);
    }
}