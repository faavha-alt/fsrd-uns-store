@extends('layouts.admin')

@section('title', 'Kategori')
@section('subtitle', 'Kelola kategori produk dan pelatihan')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Kategori</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Tambah Kategori</a>
    </div>
    <div class="panel-body" style="padding: 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin: 20px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Slug</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>
                        <span class="badge {{ $category->type === 'produk' ? 'badge-blue' : 'badge-green' }}">
                            {{ ucfirst($category->type) }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $category->slug }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-muted" style="text-align:center; padding: 30px;">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection