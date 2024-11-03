<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use Yajra\DataTables\DataTables;
use App\Models\Media;

class MediaController extends Controller
{

    public function index()
    {
        return view('admin.media.index');
    }

    public function data()
    {
        $data = Media::with('user')->get();
        dd($data);
        return DataTables::of($data)->addIndexColumn()->make(true);
    }


    public function create()
    {
        //
    }


    public function store(StoreMediaRequest $request)
    {
        //
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
}
