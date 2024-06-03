<?php

namespace App\Http\Controllers;

use App\Events\PermissionEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if (!Gate::allows('read-role')) return abort(403, 'Anda tidak memiliki hak akses!');

        return view('pages.roles.index');
    }

    public function data()
    {
        // if (!Gate::allows('read-role')) return abort(403, 'Anda tidak memiliki hak akses!');

        $data = Role::with('permissions')->get();
        return DataTables::of($data)->addIndexColumn()->toJson();
    }

    public function store(Request $request)
    {
        // if (!Gate::allows('create-role')) return abort(403, 'Anda tidak memiliki hak akses!');

        try {
            $this->validateData($request);

            $checkRole = Role::where('name', $request->role)->first();
            if ($checkRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role . ' . $request->role . ' already exists'
                ]);
            }

            $role = Role::create([
                'name' => $request->role,
                'guard_name' => 'web'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role ' . $role->name . ' created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        // if (!Gate::allows('update-role')) return abort(403, 'Anda tidak memiliki hak akses!');

        try {
            $this->validateData($request);

            $role = Role::find($request->id);
            $role->name = $request->role;
            $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Role ' . $role->name . ' updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        // if (!Gate::allows('delete-role')) return abort(403, 'Anda tidak memiliki hak akses!');

        try {
            $role = Role::find($request->id);
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role ' . $role->name . ' has been deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function validateData($request)
    {
        return $request->validate([
            'role' => 'required|string|max:255'
        ], [
            'role.required' => 'Role is required',
            'role.string' => 'Role must be string',
            'role.max' => 'Role must be less than 255 characters'
        ]);
    }

    public function assignPermission(Request $request)
    {
        if (!Gate::allows('assing-permission')) return abort(403, 'Anda tidak memiliki hak akses!');
        try {
            $validate = Validator::make($request->all(), [
                'role' => 'required',
                'permissions' => 'required|array'
            ], [
                'role.required' => 'The role field is required',
                'permissions.required' => 'The permissions field is required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $role = Role::where('name', $request->role)->first();
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role ' . $request->role . ' not found'
                ]);
            }

            $permissions = Permission::whereIn('id', $request->permissions)->get();
            if ($permissions->count() !== count($request->permissions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some permissions not found'
                ]);
            }

            $existingPermissions = $role->permissions->pluck('id')->toArray();

            $deletedPermissions = array_diff($existingPermissions, $request->permissions);

            if (!empty($deletedPermissions)) $role->permissions()->detach($deletedPermissions);

            if ($role->permissions->isEmpty()) {
                $role->permissions()->attach($permissions);
            } else {
                $role->permissions()->syncWithoutDetaching($permissions);
            }
            event(new PermissionEvent($permissions->first()));
            return response()->json([
                'success' => true,
                'message' => 'Permissions have been assigned to role ' . $role->name
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
