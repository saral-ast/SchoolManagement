<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\User;
use App\Service\AdminService;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $userService,$adminService;
    public function __construct(UserService $userService,AdminService $adminService){
        $this -> userService = $userService;
        $this -> adminService = $adminService; 
    }
    public function index(Request $request)
    {
        try {
            
            $admins = Admin::whereHas('user')->with('user')->paginate(10);
            return view('admins.index',compact('admins'));
        
        } catch (Exception $e) {
             return redirect()->back()->with('error',$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        
        DB::beginTransaction();
        try {
              // dd($request->all());
                
                $validated = $request->validated();
                $user = $this->userService->create($validated);
                
                $role = config('roles.models.role')::where('slug', '=', 'admin')->first();
                $user->attachRole($role);
                
                $this->adminService->create($user);
                
                DB::commit();
            return redirect()->route('admin.index')->with('status', 'Admin created successfully');
        } catch(ValidationException $e){
            // dd($e);
            throw $e;
        }
        catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e ?? 'An error occurred while creating your admin.')
                ->withInput();
        }  
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        // dd($user);
        return view('admins.edit',compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Admin $admin,AdminRequest $request)
    {
        try {
              // dd($request->all());
                $validated = $request->validated();

                // begin_transaction;
                $user = $admin->user;
                $this->userService->update($validated,$user);
                // you can add update admin here 
                // $this->adminService->update($data);
                
            return redirect()->route('admin.index')->with('status', 'Admin updated successfully');
        } catch(ValidationException $e){
            // dd($e);
            throw $e;
        }
         catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e ?? 'An error occurred while updatino your admin.')
                ->withInput();
        }
      

        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        DB::beginTransaction();
        try {
            $user = $admin->user;
            $this->adminService->destroy($admin);
            $this->userService->destroy($user);
            DB::commit();
            return redirect()->route('admin.index')->with('status', 'Admin deleted successfully');
 
        } catch (Exception $e) {
             DB::rollBack();
            return redirect()->back()
                ->with('error', $e ?? 'An error occurred while creating your admin.');
        }
    }
}