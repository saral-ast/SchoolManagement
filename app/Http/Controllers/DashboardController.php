<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request)  {
         try {
            
            $userType = Auth::user()->user_type();

            $userData = Auth::user()->$userType()->with('user')->get();
            $teacherCount = Teacher::count();
            $studentCount = Student::count();
            $parentCount = StudentParent::count();
            if($userType === 'admin'){
                $adminCount = Admin::count(); 
                $totalUser = User::count();

                return view('dashboard',compact('userType','userData','teacherCount','studentCount','parentCount','adminCount','totalUser'));
            }
            
            
             return view('dashboard',compact('userType','userData','teacherCount','studentCount','parentCount'));
        
        } catch (Exception $e) {
             return redirect()->back()->with('error',$e->getMessage());
        }
        
    }
}