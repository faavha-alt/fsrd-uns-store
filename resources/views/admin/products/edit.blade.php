@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kreator</label>
                <select name="creator_id" class="form-control" required>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('creator_id', $product->creator_id) == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ ucfirst($creator->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group" style="display:flex; gap:16px;">
                <div style="flex:1;">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" required>
                </div>
                <div style="flex:1;">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
                </div>
            </div>

            @if($product->images)
                <div class="form-group">
                    <label class="form-label">Foto Saat Ini</label>
                    <div style="display:flex; gap:8px;">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/'.$img) }}" style="width:70px;height:70px;border-radius:8px;object-fit:cover;">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Ganti Foto (opsional, akan mengganti semua foto lama)</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection
