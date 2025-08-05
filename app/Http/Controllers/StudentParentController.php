<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentRequest;
use App\Models\Classes;
use App\Models\StudentParent;
use App\Service\ParentService;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StudentParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $userService,$parentService;

    public function __construct(UserService $userService,ParentService $parentService){
        $this->userService = $userService;
        $this->parentService = $parentService;
    }
    public function index()
    {
        $parents = StudentParent::whereHas('user')->with('user')->paginate(10);
        // dd($parents);
        return view('parents.index',compact('parents'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allClass = Classes::all();
        return view('parents.create',compact('allClass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ParentRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $user = $this->userService->create($validated);
            $role = config('roles.models.role')::where('slug', '=', 'parent')->first();
            // dd($role);  //choose the default role upon user creation.

            $user->attachRole($role);
            $this->parentService->create($validated,$user->id);

            DB::commit();
            return redirect()->route('parent.index')->with('status','Parent Created SuccessFully');
                
            
        }   catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while creating your parent.')
                ->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentParent $parent)
    {
        // dd($parent);
        $allClass = Classes::all();
        return view('parents.edit',compact('parent','allClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ParentRequest $request, StudentParent $parent)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validated = $request->validated();          

            $user = $parent->user;
            $this->userService->update( $validated,$user); 
            $this->parentService->update($validated,$parent);
           

            DB::commit();
            return redirect()->route('parent.index')->with('status','Parent Updated SuccessFully');
                
            
        }   catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage() ?? 'An error occurred while updationg your parent.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentParent $parent)
    {
        DB::beginTransaction();
        try {
            $user = $parent->user;
            $this->parentService->destroy($parent);
            $this->userService->destroy($user);
            DB::commit();
            return redirect()->route('parent.index')->with('status', 'Parent deleted successfully');
 
        } catch (Exception $e) {
             DB::rollBack();
            return redirect()->back()
                ->with('error', $e ?? 'An error occurred while deleting your parent.');
        }
    }
}