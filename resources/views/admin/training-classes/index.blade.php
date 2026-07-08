@extends('layouts.admin')

@section('title', 'Pelatihan')
@section('subtitle', 'Kelola kelas pelatihan FSRD UNS')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Kelas Pelatihan</span>
        <a href="{{ route('admin.training-classes.create') }}" class="btn btn-primary btn-sm">+ Tambah Kelas</a>
    </div>

    <div style="padding: 16px 20px; border-bottom:1px solid var(--border); display:flex; gap:8px;">
        <a href="{{ route('admin.training-classes.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">Semua</a>
        <a href="{{ route('admin.training-classes.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline' }}">Menunggu</a>
        <a href="{{ route('admin.training-classes.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-primary' : 'btn-outline' }}">Disetujui</a>
    </div>

    <div class="panel-body" style="padding: 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin: 20px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Kategori</th>
                    <th>Instruktur</th>
                    <th>Harga</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainingClasses as $class)
                <tr>
                    <td><strong>{{ $class->name }}</strong></td>
                    <td>{{ $class->category->name ?? '-' }}</td>
                    <td>{{ $class->instructor->name ?? '-' }}</td>
                    <td class="text-price">Rp {{ number_format($class->price, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.training-classes.schedules.index', $class) }}" class="btn btn-outline btn-sm">
                            {{ $class->schedules->count() }} Jadwal
                        </a>
                    </td>
                    <td>
                        @if($class->status === 'pending')
                            <span class="badge badge-yellow">Menunggu</span>
                        @elseif($class->status === 'approved')
                            <span class="badge badge-green">Disetujui</span>
                        @else
                            <span class="badge badge-red">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($class->status === 'pending')
                            <form action="{{ route('admin.training-classes.approve', $class) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-icon confirm" title="Setujui">✓</button>
                            </form>
                        @endif
                        <a href="{{ route('admin.training-classes.edit', $class) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form action="{{ route('admin.training-classes.destroy', $class) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-muted" style="text-align:center; padding: 30px;">Belum ada kelas pelatihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
