<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateMediaRequest;
use Yajra\DataTables\DataTables;
use App\Models\Media;
use App\Models\Permission;
use App\Models\User;
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
        $mediaItems = Media::with('user')->get();
        return DataTables::of($mediaItems)->addIndexColumn()->make(true);
    }

    public function create() {}

    public function store(Request $request)
    {
        try {
            $this->validateData($request);

            // Periksa apakah nama file sudah ada
            $checkFile = Media::where('file', $request->file)->first();
            if ($checkFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'File ' . $request->file . ' already exists'
                ]);
            }

            // Proses setiap file berdasarkan tipe
            foreach ($request->file('type') as $files) {
                $fileExtension = $files->extension();
                $fileName = time() . '_' . $files->getClientOriginalName();

                // Tentukan folder berdasarkan jenis file
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                    $folder = 'uploads/gambar';
                } elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
                    $folder = 'uploads/video';
                } elseif (in_array($fileExtension, ['pdf', 'doc', 'docx'])) {
                    $folder = 'uploads/document';
                } else {
                    $folder = 'uploads/others';
                }

                // Simpan file ke dalam folder yang sesuai
                $filePath = $files->storeAs($folder, $fileName, 'public');

                // Simpan data file ke database
                $data = new Media();
                $data->file = $request->file;
                $data->user_id = auth()->user()->id;
                $data->type = $fileExtension;
                $data->file_path = $filePath;
                $data->status_izin = 'pending';
                $data->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Media ' . $data->file . ' created successfully'
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
        $media = Media::with('user')->findOrFail($id);
        $users = User::all();
        // dd($media);
        return view('admin.media.detail', compact('media', 'users'));
    }

    // Share File
    public function shareFile(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        // Jika share ke umum
        if ($request->input('share_option') === 'public') {
            $media->status_izin = 'public';
        } else {
            // Jika share ke beberapa user yang telah register/terdaftar
            $userId = str_replace('user_', '', $request->input('share_option'));
            $media->shared_users()->attach($userId);
        }
        $media->save();
        return redirect()->back()->with('success', 'File Berhasil di Share!');
    }


    // fungsi request download 
    public function reqDownload($mediaId)
    {
        $media = Media::find($mediaId);
        $user = auth()->user();

        // cek apakah user sudah pernah mengajukan sebelumnya
        $cekReq = Permission::where('user_id', $user->id)
            ->where('media_id', $mediaId)
            ->first();

        if ($cekReq) {
            return back()->with('error', 'anda telah mengajukan download file ini!');
        }

        //  Membuat req download baru
        Permission::create([
            'user_id' => $user->id,
            'media_id' => $mediaId,
            'is_approved' => Permission::STATUS_PENDING,
        ]);

        return back()->with('success', 'anda berhasil mengajukan download file!');
    }

    // fungsi tampil request download
    public function showReq($mediaId)
    {
        $media = Media::find($mediaId);

        //hanya pengupload yang bisa mengakses halaman ini
        // $this->authorize('view', $media);

        // $request = $media->downloadRequests;

        // return view('admin.media.detail')
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





    private function validateData($request)
    {
        return $request->validate([
            'file' => 'required|string|max:255',
            'type' => 'required',
            'type.*' => 'file|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,txt,mp4,mov,avi|max:2048',
            'file_path' => 'required|string|max:255',
            'status_izin' => 'required|string|max:255'
        ], [
            'file.required' => 'File is required',
            'file.string' => 'File must be string',
            'file.max' => 'File must be less than 255 characters'
        ]);
    }
}
