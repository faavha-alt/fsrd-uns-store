@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kreator (Dosen/Mahasiswa)</label>
                <select name="creator_id" class="form-control" required>
                    <option value="">-- Pilih Kreator --</option>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('creator_id') == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ ucfirst($creator->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group" style="display:flex; gap:16px;">
                <div style="flex:1;">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0" required>
                </div>
                <div style="flex:1;">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Produk (bisa lebih dari 1)</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Ajukan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection
