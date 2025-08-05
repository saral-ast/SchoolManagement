<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
        $roles = [
            'Admin'   => 'admin@admin.com',
            'Teacher' => 'teacher@school.com',
            'Student' => 'student@school.com',
             'Parent'  => 'parent@home.com',
        ];

        $permissions = config('roles.models.permission')::all();

        foreach ($roles as $roleName => $email) {
            $role = config('roles.models.role')::where('name', $roleName)->first();

            if (config('roles.models.defaultUser')::where('email', $email)->first() === null) {
                $newUser = config('roles.models.defaultUser')::create([
                    'name'       => $roleName,
                    'email'      => $email,
                    'address'    => 'Ahmedabad',
                    'birth_date' => '1990-01-01',
                    'gender'     => 'male',
                    'phone_number'      => '1234567890',
                    'password'   => bcrypt('password'),
                ]);

                $newUser->attachRole($role);

                // Give all permissions to Admin only
                if ($roleName === 'Admin') {
                    foreach ($permissions as $permission) {
                        $newUser->attachPermission($permission);
                    }
                }

                // Create related records based on role
                switch ($roleName) {
                    case 'Admin':
                        Admin::create([
                            'user_id' => $newUser->id,
                            // add other admin-specific fields if required
                        ]);
                        break;

                    case 'Teacher':
                        Teacher::create([
                            'user_id'      => $newUser->id,
                            'joining_date' => now()->toDateString(),
                            'qualification' => 'MSc',
                        ]);
                        break;
                    case 'Student':
                        // You need to provide valid student class ID and parent_id
                        // For seeding, you can get existing parent or create dummy if needed
                        // Assuming you have StudentClass with id 1 and parent id 1 for example:
                        Student::create([
                            'user_id'          => $newUser->id,
                            'admission_number' => 'ADM123',
                            'roll_number'      => 'ROLL123',
                            'class_id'         => 1,   // make sure this exists in your student_classes table

                        ]);
                    break;
                        
                    case 'Parent':
                        StudentParent::create([
                            'user_id'       => $newUser->id,
                            'occupation'    => 'Engineer',
                            'relation'      => 'Father',
                            'secondary_phone' => null,
                            'student_id'        => 1,   // make sure this parent user exists
                        ]);
                        break;

                    
                        
                }
            }
        }
    }
}