<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Illuminate\Support\Str;

class SubjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = ['Mathematics', 'English', 'Science', 'Social Studies', 'Hindi'];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['slug' => Str::slug($subject)],
                [
                    'name' => $subject,
                    'description' => $subject . ' subject',
                ]
            );
        }
    }
}