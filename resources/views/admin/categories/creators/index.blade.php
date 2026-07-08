@extends('layouts.admin')

@section('title', 'Kreator')
@section('subtitle', 'Data dosen & mahasiswa sebagai atribusi karya')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Kreator</span>
        <a href="{{ route('admin.creators.create') }}" class="btn btn-primary btn-sm">+ Tambah Kreator</a>
    </div>
    <div class="panel-body" style="padding: 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin: 20px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($creators as $creator)
                <tr>
                    <td>
                        @if($creator->photo)
                            <img src="{{ asset('storage/'.$creator->photo) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                        @else
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--navy);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                {{ substr($creator->name, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $creator->name }}</strong></td>
                    <td>
                        <span class="badge {{ $creator->type === 'dosen' ? 'badge-blue' : 'badge-green' }}">
                            {{ ucfirst($creator->type) }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $creator->department ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.creators.edit', $creator) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form action="{{ route('admin.creators.destroy', $creator) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kreator ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-muted" style="text-align:center; padding: 30px;">Belum ada data kreator.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection