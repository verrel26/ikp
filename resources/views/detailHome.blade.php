@extends('layouts.main')

@section('content')
    <main class="main">
        <section id="media-detail" class="section mt-5">
            <div class="container">
                <h2>Nama file : {{ $media->file }}</h2>
                <p>Tipe: {{ $media->type }}</p>
                <p>File Upload by : {{ $media->user->name }}</p>

                @if (in_array($media->type, ['png', 'jpg', 'jpeg']))
                    <!-- Display image -->
                    <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid d-block mx-auto"
                        alt="{{ $media->file }}" width="500">
                @elseif(in_array($media->type, ['mp4', 'mov']))
                    <!-- Display video -->
                    <video controls width="80%" class="d-block mx-auto">
                        <source src="{{ asset('storage/' . $media->file_path) }}" type="video/{{ $media->type }}">
                        Your browser does not support the video tag.
                    </video>
                @elseif($media->type === 'pdf')
                    <!-- Display PDF as embedded document -->
                    <iframe src="{{ asset('storage/' . $media->file_path) }}" width="100%" height="600px"></iframe>
                @else
                    <!-- For other types like web links or documents -->
                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank">
                        Lihat {{ $media->file }}
                    </a>
                @endif
                <div class="row my-3">
                    <div class="col-12 d-flex justify-content-between">
                        <!-- Tombol Back di kiri -->
                        <a href="/list" class="btn btn-info text-white"><i class="bi bi-arrow-left"></i>&nbsp;Back</a>

                        <!-- Tombol Download di kanan -->
                        {{-- Tombol download ada jika file ini dishared oleh user yang upload --}}
                        @if ($media->status_izin == 'pending')
                            <p>{{ $media->status_izin }}</p>
                        @elseif ($media->status_izin == 'approved')
                            <a href="{{ route('requestDownload', $media->id) }}" class="btn btn-warning text-white"><i
                                    class="bi bi-download"></i>&nbsp;Request Download</a>
                        @endif


                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
