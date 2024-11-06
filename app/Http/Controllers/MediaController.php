<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateMediaRequest;
use Yajra\DataTables\DataTables;
use App\Models\Media;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Status
{
    const approve = true;
}

class MediaController extends Controller
{
    public function index()
    {

        return view('admin.media.index');
    }

    public function data()
    {
        $userId = Auth::id();

        // Jika pengguna adalah admin (user_id = 1), ambil semua data media
        if ($userId == 1) {
            $mediaItems = Media::with('user')->get();
        } else {
            // Jika bukan admin, ambil data media yang diupload oleh pengguna yang sedang login
            $mediaItems = Media::with('user')->where('user_id', $userId)->get();
        }

        // Menggunakan DataTables untuk mengembalikan data
        return DataTables::of($mediaItems)->addIndexColumn()->make(true);
    }


    public function create() {}


    public function store(Request $request)
    {
        try {
            $this->validateData($request);
            $checkFile = Media::where('file', $request->file)->first();
            if ($checkFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'File' . $request->file . 'already exists'
                ]);
            }
            foreach ($request->file('type') as $files) {
                $fileName = time() . '_' . $files->getClientOriginalName();
                $filePath = $files->storeAs('uploads', $fileName, 'public');
            }

            $data = new Media();
            $data->file = $request->file;
            $data->user_id = auth()->user()->id;
            $data->type = $files->extension();
            $data->file_path = $filePath;
            $data->status_izin = 'pending';

            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Media' . $data->media . ' created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    public function detail(Request $request, $id)
    {
        $media = Media::where('id', $id)->firstOrFail();
        $requestCount = Permission::where('media_id', $id)->count();

        $requests = Permission::where('media_id', $id)->where('is_approved', 'pending')->with('requester')->get();
        return view('admin.media.detail', compact('media', 'requestCount', 'requests'));
    }


    public function edit(Media $media)
    {
        //
    }


    public function update(Request $request, $id) {}


    public function delete(Request $request)
    {
        try {
            $media = Media::findOrFail($request->id);
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus media',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function approve(Request $request)
    {
        try {
            $media = Media::find($request->id);

            $media->status_izin = true;

            $media->save();
            return response()->json([
                'success' => true,
                'messages' => 'Media has been updated',
                'data' => $media
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }


    // mengajukan download file 
    public function requestPermission($fileId)
    {
        // File yang mau didownload
        $file = Media::findOrFail($fileId);
        // user yang ingin mendownload
        $requestId = Auth::id();

        // cek apakah sudah mengajukan sebelumnya
        $cekRequest = Permission::where('media_id', $fileId)
            ->where('user_id', $requestId)
            ->first();

        if ($cekRequest) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan permintaan');
        }

        Permission::create([
            'media_id' => $file,
            'user_id' => $requestId,
            'owner_id' => $file->user_id

        ]);
        return redirect()->back()->with('success', 'Permintaan berhasil di ajukan');
    }

    // daftar yang ingin download 
    public function viewPermissionRequest()
    {
        $requests = Permission::with(['file', 'requester'])->where('owner_id', Auth::id())->get();
        return view('admin.media.index', compact('requests'));
    }

    // approved success
    public function approveRequest($id)
    {
        $request = Permission::findOrFail($id);
        $request->is_approved = true;
        $request->save();

        return redirect()->back()->with('success', 'Permintaan izin berhasil disetujui');
    }


    // approved tolak
    public function deliceRequest($id)
    {
        $request = Permission::findOrFail($id);
        $request->delete();
        return redirect()->back()->with('success', 'Permintaan izin berhasil ditolak');
    }

    // Share File
    public function shareFile($id)
    {
        $mediaShare = Media::findOrFail($id);
        $mediaShare->status_izin = true;
        $mediaShare->save();
        return redirect()->back()->with('success', 'Permintaan izin berhasil ditolak');
        // return redirect()->route('media.detail', $id)->with('success', 'File telah dibagikan secara publik.');
    }



    private function validateData($request)
    {
        return $request->validate([
            'file' => 'required|string|max:255',
            'type' => 'required',
            'type.*' => 'file|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,txt|max:2048',
            'file_path' => 'required|string|max:255',
            'status_izin' => 'required|string|max:255'
        ], [
            'file.required' => 'File is required',
            'file.string' => 'File must be string',
            'file.max' => 'File must be less than 255 characters'
        ]);
    }
}
