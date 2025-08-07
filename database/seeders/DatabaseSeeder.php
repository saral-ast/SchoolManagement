<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ConnectRelationshipsSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ClassesTableSeeder;
use Database\Seeders\SubjectsTableSeeder;
use Database\Seeders\TeacherSubjectSeeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();
        
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            ConnectRelationshipsSeeder::class,
            ClassesTableSeeder::class,
            UsersTableSeeder::class,
            SubjectsTableSeeder::class,
            TeacherSubjectSeeder::class, 
        ]);
        
        Model::reguard();
    }
}