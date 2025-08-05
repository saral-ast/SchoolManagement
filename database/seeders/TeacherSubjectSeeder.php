<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Assign subjects 2 and 3 to teacher with ID 1
        $teacher = Teacher::find(1);
        if ($teacher) {
            $teacher->subjects()->attach([2, 3]);
        }
    }
}