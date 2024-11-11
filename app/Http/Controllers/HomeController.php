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


    public function list()
    {
        $medialist = Media::with('user')->get();
        // dd($medialist);
        return view('list', compact('medialist'));
    }

    public function downloadFile($id)
    {
        $media = Media::findOrFail($id);

        if ($media->status_izin == 'public') {
            $filePath = storage_path('app/public/' . $media->file_path);

            if (file_exists($filePath)) {
                return response()->download($filePath, $media->file);
            } else {
                return redirect()->back()->with('message', 'File tidak ditemukan');
            }
        } else {
            return redirect()->back()->with('message', 'Anda tidak memiliki izin untuk download file ini!');
        }
    }

    public function reqDownload($id)
    {
        $reqMedia = Media::findOrFail($id);
        // dd($reqMedia); 

        if ($reqMedia->status_izin == 'pending') {
            return redirect()->back()->with('message', 'Pengajuan download sudah dikirimkan sebelumnya.');
        }
        $reqMedia->status_izin = 'pending';
        $reqMedia->save();

        return redirect()->back()->with('message', 'Pengajuan download telah dikirim ke pemilik file.');
    }
}
