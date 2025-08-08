<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Database\Seeder;

class ParentStudentSeeder extends Seeder
{
    public function run()
    {
        // Get all parents and students
        $parents = StudentParent::all();
        $students = Student::all();

        if ($parents->isEmpty() || $students->isEmpty()) {
            return;
        }

        // Create many-to-many relationships
        foreach ($parents as $parent) {
            // Each parent can have 1-3 students
            $randomStudents = $students->random(rand(1, 3));
            
            // Attach students to parent using many-to-many relationship
            $parent->students()->attach($randomStudents->pluck('id')->toArray());
        }

        // Also create some students with multiple parents (if we have enough parents)
        if ($parents->count() >= 2) {
            $studentsWithMultipleParents = $students->random(min(5, $students->count()));
            
            foreach ($studentsWithMultipleParents as $student) {
                // Assign 2 random parents to some students
                $randomParents = $parents->random(2);
                $student->parents()->attach($randomParents->pluck('id')->toArray());
            }
        }
    }
}
