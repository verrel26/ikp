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
        // dd($media);
        $shareOption = $request->input('share_option');

        // update file ke umum / user
        if ($shareOption == 'public') {
            $media->status_izin = 'public';
            $media->save();
            return redirect()->back()->with('success', 'File Berhasil di Share Umum!');
        } elseif (strpos($shareOption, 'user_') === 0) {
            $userId = str_replace('user_', '', $shareOption);

            $media->permission()->create([
                'user' => $userId,
                'status' => 'private'
            ]);
        }
        return redirect()->back()->with('error', 'Pilihan share tidak valid');
    }

    public function reqDownload($id)
    {
        $media = Media::findOrFail($id);
        $userId = Auth::user()->id;

        // $isAdmin = Auth::user()->name == 'Admin';
        // dd($isAdmin);
        if ($userId != $media->user_id) {

            $requestBy = $media->requested_by ? json_decode($media->requested_by, true) : [];


            if (!in_array($userId, $requestBy)) {

                $requestBy[] = $userId;
                $media->requested_by = json_encode($requestBy);
                $media->status_izin = 'pending';
                $media->save();

                return redirect()->route('media.detail', $id)->with('message', 'Pengajuan download berhasil dikirim');
            }

            return redirect()->route('media.detail', $id)->with('message', 'Pengajuan download telah dikirim ke pemilik file');
        }

        return redirect()->route('media.detail', $id)->with('error', 'Anda adalah pengunggah file ini.');
    }



    public function ddownload($id)
    {
        $media = Media::findOrFail($id);

        if ($media->status_izin == 'public' || $media->status_izin == 'approved') {
            return response()->download(storage_path("app/public/{$media->file_path}"));
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mendownload file ini.');
    }

    public function approve($id)
    {
        $media = Media::find($id);
        $media->status_izin = 'approved';
        $media->requested_by = auth()->id();
        $media->save();

        return redirect()->back()->with('success', 'Success');
    }
    public function decline($id)
    {

        $media = Media::find($id);
        $media->status_izin = 'declined';
        $media->save();

        return redirect()->back()->with('success', 'Success');
    }

    public function setPublic($id)
    {
        $media = Media::findOrFail($id);
        $media->status_izin = 'public';
        $media->save();

        return redirect()->back()->with('success', 'File sekarang bersifat publik.');
    }


    public function edit(Media $media) {}


    public function update(Request $request, $id)
    {
        try {
            $this->validateData($request);

            $media = Media::findOrFail($id);

            $media->file = $request->file;
            if ($request->hasFile('file')) {
                if ($media->file_path && file_exists(storage_path('app/public/' . $media->file_path))) {
                    unlink(storage_path('app/public/' . $media->file_path));
                }

                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();

                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $folder = 'uploads/gambar';
                } elseif (in_array($extension, ['mp4', 'avi', 'mov'])) {
                    $folder = 'uploads/video';
                } else {
                    $folder = 'uploads/document';
                }

                $path = $file->store($folder, 'public');
                $media->file_path = $path;
            }

            $media->save();

            echo "berhasil di edit";
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Media ' . $media->file . ' berhasil diperbarui'
            // ]);
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
