<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function profile(){
            return view('profile.edit');   
    }
    
    public function update(ProfileRequest $request){
        try {
            $user = auth()->user();
            $validated = $request->validated();
            $user->update($validated);
            return redirect()->route('profile.edit')->with('status', 'Profile updated successfully');
            
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating your profile.')
                ->withInput();
        }
    }

    public function change_password(Request $request){
        try {
              $user = auth()->user();
              $validated = $request->validate([
                  'old_password' => 'required|string|max:255',
                  'password' => 'required|string|max:255|confirmed',
              ]);
              if(!Hash::check($validated['old_password'], $user->password)){
                throw ValidationException::withMessages(['old_password' =>'The provided password does not match our records.']);
              }
              $user->password = Hash::make($validated['password']);
              $user->save();

              return redirect()->route('profile.edit')->with('password_status', 'Password Changed successfully');
            
        } 
        catch(ValidationException $e){
            throw $e;
        }
         catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e || 'An error occurred while updating your password.')
                ->withInput();
        }
    }
}