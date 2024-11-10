<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Permission;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // public function index()
    // {
    //     $media = Media::with('user')->get();
    //     // dd($media);
    //     return view('welcome', compact('media'));
    // }
    public function index()
    {
        $media = Media::whereIn('type', ['png', 'jpg', 'jpeg', 'mp4', 'mov', 'avi'])->paginate(6); // Batasi 6 item pertama
        return view('welcome', compact('media'));
    }


    public function detailHome($id)
    {
        $media = Media::with('user')->find($id);
        // dd($media);
        return view('detailHome', compact('media'));
    }

<<<<<<< HEAD
    public function list()
    {
        $medialist = Media::with('user')->get();
        // dd($medialist);
        return view('list', compact('medialist'));
=======
    public function files()
    {
        $data = Media::all();

        dd($data);
>>>>>>> 5218666cca63eb04b60de6092fbc19289f340973
    }
}
