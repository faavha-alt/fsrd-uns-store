@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name') <div class="text-muted" style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipe</label>
                <select name="type" class="form-control" required>
                    <option value="produk" {{ old('type', $category->type) === 'produk' ? 'selected' : '' }}>Produk (Lapak)</option>
                    <option value="pelatihan" {{ old('type', $category->type) === 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                </select>
                @error('type') <div class="text-muted" style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection