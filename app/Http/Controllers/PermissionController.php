<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Gate::allows('read-permission')) return abort(403, 'Anda tidak memiliki hak akses!');

        return view('pages.permission.index');
    }

    public function data()
    {
        if (!Gate::allows('read-permission')) return abort(403, 'Anda tidak memiliki hak akses!');

        $data = Permission::all();
        return DataTables::of($data)->addIndexColumn()->toJson();
    }

    public function store(Request $request)
    {
        // if (!Gate::allows('create-permission')) return abort(403, 'Anda tidak memiliki hak akses!');

        try {
            $this->validateData($request);

            $checkPermission = Permission::where('name', $request->permission)->first();
            if ($checkPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission . ' . $request->permission . ' already exists'
                ]);
            }

            $permissions = Permission::create([
                'name' => $request->permission,
                'guard_name' => 'web'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission ' . $permissions->name . ' created successfully'
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
        // if (!Gate::allows('update-permission')) return abort(403, 'Anda tidak memiliki hak akses!');

        try {
            $this->validateData($request);

            $permissions = Permission::find($request->id);
            $permissions->name = $request->permission;
            $permissions->save();

            return response()->json([
                'success' => true,
                'message' => 'Permission ' . $permissions->name . ' updated successfully'
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
        // if (!Gate::allows('delete-permission')) return abort(403, 'Anda tidak memiliki hak akses!');

        try {
            $permission = Permission::find($request->id);
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission ' . $permission->name . ' has been deleted'
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
            'permission' => 'required|string|max:255'
        ], [
            'permission.required' => 'Permission is required',
            'permission.string' => 'Permission must be string',
            'permission.max' => 'Permission must be less than 255 characters'
        ]);
    }
}
