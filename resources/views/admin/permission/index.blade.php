@extends('layouts.admin.app')
@section('title', 'Permintaan Izin Download')

@section('content')
    <div class="card-header">Permintaan Izin Download</div>

    <div class="card-body">
        @if ($requests->isEmpty())
            <p>Tidak ada permintaan izin saat ini.</p>
        @else
            <ul>
                @foreach ($requests as $request)
                    <li>
                        File: {{ $request->file->file }} - Diminta oleh: {{ $request->requester->name }}
                        <form action="{{ route('permissions.approve', $request->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                        </form>
                        <form action="{{ route('permissions.decline', $request->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
