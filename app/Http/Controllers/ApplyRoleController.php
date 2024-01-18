<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ApplyRole;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class ApplyRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->hasRole('admin')){
            $pendingRoles = ApplyRole::with(['users'])->where('role_status', 'pending')->get();
            
            return Inertia::render('Role/Approve',[
                'pendingRoles' => $pendingRoles
            ]);
        }
        else {
            $user_id = auth()->user()->id;
            $existingApplication = ApplyRole::where('user_id', $user_id)
            ->where('role_status', 'pending')
            ->exists();
            if ($existingApplication) {
                // User already applied for role
                return redirect()->route('dashboard')->with('message', 'Already applied for role. Please wait for verification');
            }
            // Assuming 'admin' is the name of the admin role
            $roles = Role::with('permissions')->where('name', '<>', 'admin')->get();
            return Inertia::render('Role/Create', [
                'role' => $roles
            ]);
        }    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = new ApplyRole([
            'user_id' => auth()->user()->id,
            'email' => $request->email,
            'address' => $request->address,
            'company_id' => $request->id,
            'role_applied' => $request->post_name,
            // 'role_status' => 'pending',
           

        ]);
        $role->save();
        return redirect()->route('dashboard')->with('message', 'Successfully Applied for role!');

    }

    /**
     * Display the specified resource.
     */
    public function show(ApplyRole $applyRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplyRole $applyRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApplyRole $applyRole): RedirectResponse
    {
        //If approved assigned the given role and notify the user if rejected Notify again the user to apply again.
        // dd($applyRole->user_id);
        // dd($request->status);

        if($request->status === "Approve"){
            $user = User::find($applyRole->user_id);
            $user->syncRoles($applyRole->role_applied);
            // $user->assignRole($applyRole->role_applied);
            $applyRole->role_status = 'accepted';
            $applyRole->save();
            return redirect()->route('dashboard')->with('message', 'Role Applied');

        }else { // if rejected
            $applyRole->role_status = 'rejected';
            return redirect()->route('dashboard')->with('message', 'Role Rejected');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplyRole $applyRole)
    {
        //
    }
}