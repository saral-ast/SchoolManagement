<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Get all subject IDs once
        $subjectIds = \DB::table('subjects')->pluck('id')->toArray();

        // For each teacher, attach 2 random subjects
        Teacher::all()->each(function ($teacher) use ($subjectIds) {
            if (count($subjectIds) >= 2) {
                // Pick 2 random unique subject IDs
                $randomSubjects = collect($subjectIds)->random(2)->toArray();
                $teacher->subjects()->attach($randomSubjects);
            }
        });
    }
}