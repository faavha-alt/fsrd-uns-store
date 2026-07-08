@extends('layouts.admin')

@section('title', 'Edit Kelas Pelatihan')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.training-classes.update', $trainingClass) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Kelas</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $trainingClass->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $trainingClass->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Instruktur</label>
                <select name="creator_id" class="form-control" required>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('creator_id', $trainingClass->creator_id) == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ ucfirst($creator->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $trainingClass->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Silabus</label>
                <textarea name="syllabus" class="form-control" rows="3">{{ old('syllabus', $trainingClass->syllabus) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $trainingClass->price) }}" min="0" required>
            </div>

            @if($trainingClass->image)
                <div class="form-group">
                    <label class="form-label">Gambar Saat Ini</label>
                    <div><img src="{{ asset('storage/'.$trainingClass->image) }}" style="width:100px;height:100px;border-radius:8px;object-fit:cover;"></div>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Ganti Gambar (opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.training-classes.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection
