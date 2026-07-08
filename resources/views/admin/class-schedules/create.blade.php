@extends('layouts.admin')

@section('title', 'Tambah Jadwal: ' . $trainingClass->name)

@section('content')
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('admin.training-classes.schedules.store', $trainingClass) }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
            </div>

            <div class="form-group" style="display:flex; gap:16px;">
                <div style="flex:1;">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                </div>
                <div style="flex:1;">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Contoh: Studio FSRD UNS, Surakarta" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kuota Peserta</label>
                <input type="number" name="quota" class="form-control" value="{{ old('quota') }}" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            <a href="{{ route('admin.training-classes.schedules.index', $trainingClass) }}" class="btn btn-outline">Batal</a>
        </form>
    </div>
</div>
@endsection
