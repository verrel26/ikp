@extends('layouts.admin.app')
@section('title', 'Media')

@section('content')
    <div class="card-header">Detail <b>{{ $media->file }}</b></div>
    <div class="card-body">
        <div class="container">
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                </script>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if (in_array(pathinfo($media->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="File Image"
                            style="max-width: 50%; height: auto;">
                    @elseif (in_array(pathinfo($media->file_path, PATHINFO_EXTENSION), ['mp4', 'mov']))
                        <video controls width="70%" class="d-block mx-auto">
                            <source src="{{ asset('storage/' . $media->file_path) }}" type="video/{{ $media->type }}">
                            Your browser does not support the video tag.
                        </video>
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
                    <h5>Status Izin: <b>{{ $media->status_izin }}</b></h5>
                    <br>

                    @if (auth()->user()->id == $media->user_id)
                        @if ($media->status_izin == 'pending' && $media->requested_by)
                            <div class="mt-3">
                                <ul>
                                    @foreach (json_decode($media->requested_by, true) as $userId)
                                        <li>{{ \App\Models\User::find($userId)->name ?? 'Pengguna tidak ditemukan' }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <form action="{{ route('media.approve', $media->id) }}" method="POST" style="display: inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm my-2">
                                    <i class="nav-icon fas fa-check-circle"></i>&nbsp; Setuju
                                </button>&nbsp;
                            </form>
                            <form action="{{ route('media.decline', $media->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm my-2">
                                    <i class="nav-icon fas fa-exclamation-circle"></i>&nbsp; Tolak
                                </button>&nbsp;
                            </form>
                        @else
                            <p>Tidak ada pengajuan download.</p>
                        @endif
                    @endif


                    {{-- Tombol "Jadikan Publik" jika status izin adalah approved, untuk pemilik file atau admin --}}
                    @if ($media->status_izin == 'approved' && auth()->user()->id == $media->user_id)
                        <form action="{{ route('media.setPublic', $media->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm my-2">
                                <i class="nav-icon fas fa-share-alt-square"></i>&nbsp; Jadikan Publik
                            </button>
                        </form>
                    @endif

                </div>
            </div>
            <div class="row">
                <div class="col-md-6 my-2">
                    <a href="{{ route('media.index', auth()->user()->name) }}" class="btn btn-info btn-sm"><i
                            class="nav-icon fas fa-chevron-circle-left"></i>&nbsp;Back</a>
                </div>
                <div class="col-md-6 my-2">
                    {{-- Tampilkan tombol download jika status izin adalah public atau approved --}}
                    @if ($media->status_izin == 'public' || ($media->status_izin == 'approved' && auth()->user()->id != $media->user_id))
                        <a href="{{ route('media.ddownload', $media->id) }}" class="btn btn-warning">
                            <i class="nav-icon fas fa-download"></i>&nbsp;Download
                        </a>
                    @endif
                    {{-- Tombol Ajukan Download untuk User yang Bukan Pemilik File --}}

                    @if ($media->status_izin == 'pending' && auth()->user()->id != $media->user_id)
                        <form action="{{ route('media.reqDownload', $media->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="nav-icon fas fa-cloud-download-alt"></i>&nbsp;Ajukan Download
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
