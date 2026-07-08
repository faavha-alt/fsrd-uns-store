@extends('layouts.admin')

@section('title', 'Tambah Kreator')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.creators.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tipe</label>
                <select name="type" class="form-control" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="dosen" {{ old('type') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ old('type') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jurusan / Program Studi</label>
                <input type="text" name="department" class="form-control" value="{{ old('department') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Bio Singkat</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Foto</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.creators.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection