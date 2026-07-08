@extends('layouts.admin')

@section('title', 'Tambah Kelas Pelatihan')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.training-classes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Kelas</label>
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
                <label class="form-label">Instruktur</label>
                <select name="creator_id" class="form-control" required>
                    <option value="">-- Pilih Instruktur --</option>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('creator_id') == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ ucfirst($creator->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Silabus</label>
                <textarea name="syllabus" class="form-control" rows="3" placeholder="Materi yang akan diajarkan...">{{ old('syllabus') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Kelas</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Ajukan Kelas</button>
            <a href="{{ route('admin.training-classes.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection@extends('layouts.admin')

@section('title', 'Tambah Kelas Pelatihan')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.training-classes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Kelas</label>
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
                <label class="form-label">Instruktur</label>
                <select name="creator_id" class="form-control" required>
                    <option value="">-- Pilih Instruktur --</option>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('creator_id') == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ ucfirst($creator->type) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Silabus</label>
                <textarea name="syllabus" class="form-control" rows="3" placeholder="Materi yang akan diajarkan...">{{ old('syllabus') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Kelas</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Ajukan Kelas</button>
            <a href="{{ route('admin.training-classes.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection
