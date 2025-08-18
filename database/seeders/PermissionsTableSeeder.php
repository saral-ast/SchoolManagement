<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Permission Types
         *
         */
        $Permissionitems = [
            [
                'name'        => 'Can View Users',
                'slug'        => 'view.users',
                'description' => 'Can view users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Create Users',
                'slug'        => 'create.users',
                'description' => 'Can create new users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Edit Users',
                'slug'        => 'edit.users',
                'description' => 'Can edit users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Delete Users',
                'slug'        => 'delete.users',
                'description' => 'Can delete users',
                'model'       => 'Permission',
            ],

            // Admin
            [
                'name'        => 'Can View Admins',
                'slug'        => 'view.admins',
                'description' => 'Can view admins',
                'model'       => 'Admin',
            ],
            [
                'name'        => 'Can Create Admins',
                'slug'        => 'create.admins',
                'description' => 'Can create admins',
                'model'       => 'Admin',
            ],
            [
                'name'        => 'Can Edit Admins',
                'slug'        => 'edit.admins',
                'description' => 'Can edit admins',
                'model'       => 'Admin',
            ],
            [
                'name'        => 'Can Delete Admins',
                'slug'        => 'delete.admins',
                'description' => 'Can delete admins',
                'model'       => 'Admin',
            ],

            // Teacher
            [
                'name'        => 'Can View Teachers',
                'slug'        => 'view.teachers',
                'description' => 'Can view teachers',
                'model'       => 'Teacher',
            ],
            [
                'name'        => 'Can Create Teachers',
                'slug'        => 'create.teachers',
                'description' => 'Can create teachers',
                'model'       => 'Teacher',
            ],
            [
                'name'        => 'Can Edit Teachers',
                'slug'        => 'edit.teachers',
                'description' => 'Can edit teachers',
                'model'       => 'Teacher',
            ],
            [
                'name'        => 'Can Delete Teachers',
                'slug'        => 'delete.teachers',
                'description' => 'Can delete teachers',
                'model'       => 'Teacher',
            ],

            // Student
            [
                'name'        => 'Can View Students',
                'slug'        => 'view.students',
                'description' => 'Can view students',
                'model'       => 'Student',
            ],
            [
                'name'        => 'Can Create Students',
                'slug'        => 'create.students',
                'description' => 'Can create students',
                'model'       => 'Student',
            ],
            [
                'name'        => 'Can Edit Students',
                'slug'        => 'edit.students',
                'description' => 'Can edit students',
                'model'       => 'Student',
            ],
            [
                'name'        => 'Can Delete Students',
                'slug'        => 'delete.students',
                'description' => 'Can delete students',
                'model'       => 'Student',
            ],

            // Parent
            [
                'name'        => 'Can View Parents',
                'slug'        => 'view.parents',
                'description' => 'Can view parents',
                'model'       => 'Parent',
            ],
            [
                'name'        => 'Can Create Parents',
                'slug'        => 'create.parents',
                'description' => 'Can create parents',
                'model'       => 'Parent',
            ],
            [
                'name'        => 'Can Edit Parents',
                'slug'        => 'edit.parents',
                'description' => 'Can edit parents',
                'model'       => 'Parent',
            ],
            [
                'name'        => 'Can Delete Parents',
                'slug'        => 'delete.parents',
                'description' => 'Can delete parents',
                'model'       => 'Parent',
            ],

            // Result (no delete)
            [
                'name'        => 'Can View Results',
                'slug'        => 'view.results',
                'description' => 'Can view results',
                'model'       => 'Result',
            ],
            [
                'name'        => 'Can Create Results',
                'slug'        => 'create.results',
                'description' => 'Can create results',
                'model'       => 'Result',
            ],

            // Schedule
            [
                'name'        => 'Can View Schedules',
                'slug'        => 'view.schedules',
                'description' => 'Can view time tables',
                'model'       => 'Schedule',
            ],
            [
                'name'        => 'Can Create Schedules',
                'slug'        => 'create.schedules',
                'description' => 'Can create time tables',
                'model'       => 'Schedule',
            ],
            [
                'name'        => 'Can Edit Results',
                'slug'        => 'edit.results',
                'description' => 'Can edit results',
                'model'       => 'Result',
            ],
            [
                'name'        => 'Can Create Quizzes',
                'slug'        => 'create.quizzes',
                'description' => 'Can create quizzes',
                'model'       => 'Quiz',
            ],
            [
                'name'        => 'Can View Quizzes',
                'slug'        => 'view.quizzes',
                'description' => 'Can view quizzes',
                'model'       => 'Quiz',
            ],
            [
                'name'        => 'Can Edit Quizzes',
                'slug'        => 'edit.quizzes',
                'description' => 'Can edit quizzes',
                'model'       => 'Quiz',
            ],
            [
                'name'        => 'Can Attempt Quizzes',
                'slug'        => 'attempt.quizzes',
                'description' => 'Can attempt quizzes',
                'model'       => 'Quiz',
            ],
        ];



        /*
         * Add Permission Items
         *
         */
        foreach ($Permissionitems as $Permissionitem) {
            $newPermissionitem = config('roles.models.permission')::where('slug', '=', $Permissionitem['slug'])->first();
            if ($newPermissionitem === null) {
                $newPermissionitem = config('roles.models.permission')::create([
                    'name'          => $Permissionitem['name'],
                    'slug'          => $Permissionitem['slug'],
                    'description'   => $Permissionitem['description'],
                    'model'         => $Permissionitem['model'],
                ]);
            }
        }
    }
}
