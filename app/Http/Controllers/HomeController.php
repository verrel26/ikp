<?php

namespace App\Http\Controllers;

use App\Models\Media;
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
        $media = Media::find($id);
        // dd($media);
        return view('detailHome', compact('media'));
    }
}
