@extends('layouts.admin')

@section('title', 'Edit Kreator')

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.creators.update', $creator) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $creator->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tipe</label>
                <select name="type" class="form-control" required>
                    <option value="dosen" {{ old('type', $creator->type) === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ old('type', $creator->type) === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jurusan / Program Studi</label>
                <input type="text" name="department" class="form-control" value="{{ old('department', $creator->department) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Bio Singkat</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio', $creator->bio) }}</textarea>
            </div>

            @if($creator->photo)
                <div class="form-group">
                    <label class="form-label">Foto Saat Ini</label>
                    <div><img src="{{ asset('storage/'.$creator->photo) }}" style="width:80px;height:80px;border-radius:8px;object-fit:cover;"></div>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Ganti Foto (opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.creators.index') }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection
