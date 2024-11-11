@extends('layouts.main')

@section('content')
    <main class="main">
        <section id="media-detail" class="section mt-5">
            <div class="container">
                @if (session('message'))
                    <div class="alert alert-info">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <h2>Nama file : {{ $media->file }}</h2>
                <p>Tipe: <b>{{ $media->type }}</b></p>
                <p>File Upload by : <b>{{ $media->user->name }}</b></p>

                @if (in_array($media->type, ['png', 'jpg', 'jpeg']))
                    <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid d-block mx-auto"
                        alt="{{ $media->file }}" width="500">
                @elseif(in_array($media->type, ['mp4', 'mov']))
                    <video controls width="70%" class="d-block mx-auto">
                        <source src="{{ asset('storage/' . $media->file_path) }}" type="video/{{ $media->type }}">
                        Your browser does not support the video tag.
                    </video>
                @elseif($media->type === 'pdf')
                    <iframe src="{{ asset('storage/' . $media->file_path) }}" width="100%" height="600px"></iframe>
                @else
                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank">
                        Lihat {{ $media->file }}
                    </a>
                @endif


                <div class="row my-3">
                    <div class="col-12 d-flex justify-content-between">
                        <a href="/list" class="btn btn-info text-white"><i class="bi bi-arrow-left"></i>&nbsp;Back</a>

                        @if ($media->status_izin == 'public')
                            <a href="{{ route('downloadFile', $media->id) }}" class="btn btn-warning text-white">
                                <i class="bi bi-download"></i>&nbsp; Download
                            </a>
                        @elseif($media->status_izin == 'pending')
                            <p class="text-muted">Pengajuan download telah dikirimkan.</p>
                        @else
                            <form action="{{ route('reqDownload', $media->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-download"></i>&nbsp;Ajukan Download
                                </button>
                            </form>
                        @endif

                    </div>
                </div>

                @if (session('message'))
                    <div class="alert alert-info mt-3">
                        {{ session('message') }}
                    </div>
                    <!-- Script untuk auto-refresh halaman jika ada pesan -->
                    <script>
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // Refresh halaman setelah 2 detik
                    </script>
                @endif

            </div>
        </section>
    </main>
@endsection
