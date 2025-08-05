<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $RoleItems = [
            [
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin Role',
                'level'       => 4,
            ],
            [
                'name'        => 'Teacher',
                'slug'        => 'teacher',
                'description' => 'Teacher Role',
                'level'       => 3,
            ],
            [
                'name'        => 'Parent',
                'slug'        => 'parent',
                'description' => 'Parent Role',
                'level'       => 2,
            ],
            [
                'name'        => 'Student',
                'slug'        => 'student',
                'description' => 'Student Role',
                'level'       => 1,
            ]
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = config('roles.models.role')::where('slug', '=', $RoleItem['slug'])->first();
            if ($newRoleItem === null) {
                $newRoleItem = config('roles.models.role')::create([
                    'name'          => $RoleItem['name'],
                    'slug'          => $RoleItem['slug'],
                    'description'   => $RoleItem['description'],
                    'level'         => $RoleItem['level'],
                ]);
            }
        }
    }
}