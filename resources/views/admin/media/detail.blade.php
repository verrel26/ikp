@extends('layouts.admin.app')
@section('title', 'Media')

@section('content')
    <div class="card-header">Detail <b>{{ $media->file }}</b></div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                @if (in_array(pathinfo($media->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $media->file_path) }}" alt="File Image"
                        style="max-width: 50%; height: auto;">
                @else
                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank">Download File</a>
                @endif
            </div>
            <div class="col-md-6">
                <h5>Nama user: <b>{{ $media->user->name }}</b></h5>
                <h5>Type: {{ $media->type }}</h5>
                <h5>File Path: {{ $media->file_path }}</h5>
                {{-- <h5>Status Izin: {{ $media->status_izin }}</h5> --}}
                <h5>Status Izin: {{ $media->status_izin ? 'Diberikan' : 'Ditolak' }}</h5>


                @if ($media->user_id != Auth::id())
                    <!-- Tombol minta izin jika file dimiliki oleh user lain -->
                    <form action="{{ route('media.requestPermission', $media->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Minta Izin Download</button>
                    </form>
                @else
                    <a href="{{ asset('storage/' . $media->file_path) }}" class="btn btn-primary btn-sm">Download</a>
                @endif

                <form action="{{ route('media.shareFile', $media->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm">Share File</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <h5>Jumlah Pengajuan Izin: {{ $requestCount }}</h5>
            @if ($requests->isEmpty())
                <br>
                <p>Tidak ada permintaan izin saat ini.</p>
            @else
                <ul>
                    @foreach ($requests as $request)
                        <li>
                            File: {{ $request->file->file }} - Diminta oleh: {{ $request->requester->name }}
                            <form action="{{ route('permissions.approve', $request->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('permissions.decline', $request->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
