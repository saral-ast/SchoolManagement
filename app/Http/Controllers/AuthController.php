<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function loginAttempt(AuthRequest $request){
        try {
             $credentaial = $request->validated();
            //  dd($credentaial);
             if(!auth()->attempt($credentaial)){
                  throw new Exception('Invalid Credentials');
             }

             $user = User::where('email', $credentaial['email'])->first();

            auth()->login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function logout(Request $request){
         auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    
}