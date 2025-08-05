<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes as StudentClass;
use Illuminate\Support\Str;

class ClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];

        foreach ($classes as $class) {
            StudentClass::updateOrCreate(
                ['slug' => Str::slug($class)],
                [
                    'name' => $class,
                    'description' => $class . ' description',
                ]
            );
        }
    }
}