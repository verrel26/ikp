<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // public function index()
        // {
        // buat kondisi jika role user admin masuk ke menu admin jika selain admin masuk ke menu lainnya

        // if (auth()->user()->hasRole('admin')) {
        //     return view('pages.homes.index');
        // } else {
        //     return view('pages.karyawan.index');
        // }
        // return view('/');
        // }s
        return view('home');
    }
}
