<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function data()
    {
        $data = User::all();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $this->validateData($request);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan user',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function show(Request $request)
    {
        //
    }


    public function edit(Request $request)
    {
        //
    }


    public function update(Request $request)
    {
        try {
            $this->validateData($request);

            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User ' . $user->file . ' berhasil di update'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function delete(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus user',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    private function validateData($request)
    {
        return $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
        ], [
            'name.reqired' => 'Nama harus di isi',
            'email.email' => 'Email tidak valid',
        ]);
    }
}
