<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateMediaRequest;
use Yajra\DataTables\DataTables;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $data = Media::with('user')->get();
        // dd($data);
        return view('admin.media.index', compact('data'));
    }

    public function data()
    {
        $data = Media::with('user')->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }


    public function create() {}


    public function store(Request $request)
    {
        // $request->validateData([
        //     'file' => 'required|string',
        //     'type' => 'required|file|mines:jpg,jpeg,png,pdf,doc,docx',
        // ]);

        if ($request->hasFile('type')) {
            $type = $request->file('type');
            $typeName = time() .  '_' . $type->getClientOriginalName();
            $typePath = $type->storeAs('uploads', $typeName, 'public');
        } else {
            return back()->with('error', 'File is required');
        }

        $data = new Media();
        $data->file = $request->file;
        $data->user_id = auth()->user()->id;
        $data->type = $typeName;
        $data->file_path = $typePath;
        $data->status_izin = 'pending';

        $data->save();

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }



    public function show(Media $media)
    {
        //
    }


    public function edit(Media $media)
    {
        //
    }


    public function update(UpdateMediaRequest $request, Media $media)
    {
        //
    }


    public function destroy(Media $media)
    {
        //
    }


    private function validateData($request)
    {
        return $request->validate([
            'file' => 'required|string|max:255',
            'type' => 'required|',
            'file_path' => 'required|string|max:255',
            'status_izin' => 'required|string|max:255'
        ], [
            'file.required' => 'File is required',
            'file.string' => 'File must be string',
            'file.max' => 'File must be less than 255 characters'
        ]);
    }
}
