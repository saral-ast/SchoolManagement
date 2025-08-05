<?php

namespace App\Service;

use App\Models\User;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function create($data){
    //    dd($data);
        return 
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'address' => $data['address'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'],
        ]);
    }

    public function update($data,$user){
        return $user->update(
             [
                'name' => $data['name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'phone_number' => $data['phone_number'],
            ]
        );
    }

    public function destroy($user){
        $user->delete();
    }
}