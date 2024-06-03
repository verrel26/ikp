<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if (!Gate::allows('read-user')) return abort(403, 'Anda tidak memiliki hak akses!');
        if (request()->ajax()) {
            return $this->dataTable();
        }
        $roles = Role::get();
        return view('pages.user.index', compact('roles'));
    }


    public function data()
    {
        // if (!Gate::allows('read-user')) return abort(403, 'Anda tidak memiliki hak akses!');

        // $data = User::get();
        $data = User::get();
        // dd($data)

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        try {
            $this->validateData($request);
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->assignRole($request->role);
            DB::commit();
            return redirect('/user')->with('success', 'New Foto has been added');
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Berhasil menambahkan user',
            // ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function update(Request $request)
    {
        try {
            $this->validateUpdateData($request);

            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $user->syncRoles($request->role);
            DB::commit();
            return redirect('/user')->with('success', 'New Foto has been added');
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Berhasil mengubah user',
            // ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        // if (!Gate::allows('delete-user')) return abort(403, 'Anda tidak memiliki hak akses!');
        try {
            $user = User::find($request->id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User ' . $user->name . ' has been deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        return redirect('/user')->with('success', 'New Post Has Deleted');
    }


    private function validateData($request)
    {
        return $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'nullable|email',
            'password' => 'required|string',
            'role' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password harus diisi',
            'role.required' => 'Role harus diisi',
        ]);
    }

    private function validateUpdateData($request)
    {
        return $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'nullable|email',
            'role' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'email.email' => 'Email tidak valid',
            'role.required' => 'Role harus diisi',
        ]);
    }
}
