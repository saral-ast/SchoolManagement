<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Step 1: Create 30 random users via factory
        // User::factory()->count(30)->create();

        // Step 2: Define roles and counts for manual creation
        $userRoles = [
            'Admin'   => 1,
            'Teacher' => 9,
            'Student' => 10,
            'Parent'  => 10,
        ];

        $permissions = config('roles.models.permission')::all();

        foreach ($userRoles as $roleName => $count) {
            $role = config('roles.models.role')::where('name', $roleName)->first();

            for ($i = 0; $i < $count; $i++) {
                $user = User::create([
                    'name'         => $roleName . ' ' . ($i + 1),
                    'email'        => strtolower($roleName) . $i . '@example.com',
                    'address'      => 'Ahmedabad',
                    'birth_date'   => '1990-01-01',
                    'gender'       => 'male',
                    'phone_number' => '1234567890',
                    'password'     => Hash::make('password'),
                ]);

                $user->attachRole($role);

                if ($roleName === 'Admin') {
                    foreach ($permissions as $permission) {
                        $user->attachPermission($permission);
                    }

                    Admin::create([
                        'user_id' => $user->id,
                    ]);
                }

                if ($roleName === 'Teacher') {
                    Teacher::create([
                        'user_id'      => $user->id,
                        'joining_date' => now()->toDateString(),
                        'qualification'=> 'MSc',
                    ]);
                }

                if ($roleName === 'Student') {
                    // Get random class_id from student_classes table
                    $classId = \DB::table('classes')->inRandomOrder()->value('id') ?? 1;

                    Student::create([
                        'user_id'          => $user->id,
                        'admission_number' => 'ADM' . rand(1000, 9999),
                        'roll_number'      => 'ROLL' . rand(1000, 9999),
                        'class_id'         => $classId,
                    ]);
                }

                if ($roleName === 'Parent') {
                    StudentParent::create([
                        'user_id'        => $user->id,
                        'occupation'     => 'Engineer',
                        'relation'       => 'Father',
                        'secondary_phone'=> null,
                    ]);
                }
            }
        }
    }
}