@extends('layouts.admin.app')
@section('title', 'Media')

@section('content')
    <div class="card-header">Detail <b>{{ $media->file }}</b></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 ">
                @if (in_array(pathinfo($media->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $media->file_path) }}" alt="File Image"
                        style="max-width: 50%; height: auto;">
                @elseif(pathinfo($media->file_path, PATHINFO_EXTENSION) == 'doc' ||
                        pathinfo($media->file_path, PATHINFO_EXTENSION) == 'docx')
                    <i class="fas fa-file-word" style="font-size: 50px; color: blue;"></i>
                @elseif(pathinfo($media->file_path, PATHINFO_EXTENSION) == 'pdf')
                    <i class="fas fa-file-pdf" style="font-size: 50px; color: red;"></i>
                @else
                    <i class="fas fa-file" style="font-size: 50px; color: gray;"></i>
                @endif
            </div>
            <div class="col-md-6">
                <h5>Nama user: <b>{{ $media->user->name }}</b></h5>
                <h5>Type: <b>{{ $media->type }}</b></h5>
                {{-- Status izin download --}}
                <h5>Status Izin: <b>{{ $media->status_izin }}</b></h5>

                {{-- kondisi --}}
                @if ($media->status_izin == 'public' || ($media->status_izin == 'approved' && auth()->id() != $media->user_id))
                    <a href="{{ route('media.ddownload', $media->id) }}" class="btn btn-primary"><i
                            class="nav-icon fas fa-file-alt"></i>&nbsp;
                        Download</a>
                @elseif(auth()->id() != $media->user_id && $media->status_izin == 'pending')
                    <form action="{{ route('media.Reqdownload', $media->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning"><i class="nav-icon fas fa-download"></i>&nbsp;
                            Ajukan Download</button>
                    </form>
                @endif


            </div>
        </div>
        <div class="row mt-4">
            {{-- Fungsi Approved / Delice --}}
            @if (auth()->user()->id == $media->user_id && $media->status_izin == 'pending')
                <form action="{{ route('media.approve', $media->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Setuju</button>
                </form>
                <form action="{{ route('media.decline', $media->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                </form>
            @endif



        </div>
        <a href="{{ route('media.index', auth()->user()->name) }}" class="btn btn-info btn-sm"><i
                class="nav-icon fas fa-chevron-circle-left"></i>&nbsp;Back</a>
    </div>

@endsection
