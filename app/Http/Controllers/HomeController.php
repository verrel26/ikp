<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class HomeController extends Controller
{
        public function index()
        {
            $media = Media::with('user')->get();
            // dd($media);
            return view('welcome', compact('media'));
        }
}
