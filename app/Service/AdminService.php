<?php

namespace App\Service;

use App\Models\Admin;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function create($user){
       return  Admin::create([
            'user_id' => $user->id,
        ]);
    }

    public function destroy($admin){
        return $admin->delete();
    }
}