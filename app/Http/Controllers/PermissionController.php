<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        $roles = Role::pluck('name', 'id');
        return Inertia::render('Permission/Index', [
            'permissions' => $permissions,
            'roles' => $roles,
        ]);

        // return view('PermissionsUI::permissions.index', compact('permissions'));
    }

    // public function create(): View
    // {
    //     $roles = Role::pluck('name', 'id');

    //     return view('PermissionsUI::permissions.create', compact('roles'));
    // }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->validate([
            'name' => ['required', 'string'],
            'roles' => ['array'],
        ]);

        $permission = Permission::create($data);

        $permission->syncRoles($request->input('roles'));

        return redirect()->route('permissions');
    }

    // public function edit(Permission $permission): View
    // {
    //     $roles = Role::pluck('name', 'id');

    //     return view('PermissionsUI::permissions.edit', compact('permission', 'roles'));
    // }

    public function update(Request $request, Permission $permission)
    {
        // dd($request);
        // dd($permission->where('id', $request->selectedPermission)->get());
        $update_permission = $permission->where('id', $request->selectedPermission)->first();
        $data = $request->validate([
            'name' => ['required', 'string'],
            'roles' => ['array'],
        ]);

        $update_permission->update($data);

        $update_permission->syncRoles($request->input('roles'));

        // return redirect()->route(config('permission_ui.route_name_prefix') . 'permissions.index');
        return redirect()->route('permissions');
    }

    public function destroy( $id, Permission $permission)
    {
        // dd($id);
        $permission->where('id', $id)->first()->delete();
        // $permission->delete();
        return redirect()->route('permissions');
        // return redirect()->route(config('permission_ui.route_name_prefix') . 'permissions.index');
    }
}