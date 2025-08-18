<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class ConnectRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissionModel = config('roles.models.permission');
        $roleModel = config('roles.models.role');

        // Get all permissions
        $permissions = $permissionModel::all();

        // Admin gets all permissions
        $roleAdmin = $roleModel::where('name', 'Admin')->first();
        if ($roleAdmin) {
            $roleAdmin->permissions()->sync($permissions->pluck('id')->toArray());
        }

        // Permission mapping by role
        $rolePermissions = [
            'Teacher' => [
                'view.students',
                'create.students',
                'edit.students',
                'view.results',
                'create.results',
                'edit.results',
                'view.parents',
                'view.schedules',
                'create.quizzes',
                'edit.quizzes',
                'view.quizzes',
            ],
            'Parent' => [
                'view.students',
                'view.results',
                'view.schedules',
                'view.quizzes',
            ],
            'Student' => [
                'view.results',
                'view.schedules',
                'view.quizzes',
                'attempt.quizzes',
            ],
        ];

        // Assign permissions based on role
        foreach ($rolePermissions as $roleName => $slugs) {
            $role = $roleModel::where('name', $roleName)->first();
            if ($role) {
                $permissionIds = $permissionModel::whereIn('slug', $slugs)->pluck('id')->toArray();
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
