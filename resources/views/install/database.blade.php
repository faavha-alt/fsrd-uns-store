@extends('install.layout')

@php($step = 3)

@section('title', 'Konfigurasi Database')
@section('heading', 'Konfigurasi Database')
@section('subheading', 'Masukkan kredensial database MySQL yang akan dipakai')

@section('content')
    <form method="POST" action="{{ route('install.database.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Host Database</label>
            <input type="text" name="db_host" class="form-input" value="{{ old('db_host', $old['db_host'] ?? '127.0.0.1') }}" required autofocus>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Port</label>
                <input type="text" name="db_port" class="form-input" value="{{ old('db_port', $old['db_port'] ?? '3306') }}" required>
            </div>
            <div class="form-group" style="flex:2;">
                <label class="form-label">Nama Database</label>
                <input type="text" name="db_database" class="form-input" value="{{ old('db_database', $old['db_database'] ?? '') }}" placeholder="fsrduns" required>
                <div class="form-hint">Akan dibuat otomatis jika belum ada</div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Username Database</label>
            <input type="text" name="db_username" class="form-input" value="{{ old('db_username', $old['db_username'] ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Password Database</label>
            <input type="password" name="db_password" class="form-input" value="{{ old('db_password', $old['db_password'] ?? '') }}">
        </div>

        <button type="submit" class="btn-submit">Tes Koneksi & Lanjutkan →</button>
    </form>
    <a href="{{ route('install.license') }}" class="btn-back">← Kembali</a>
@endsection
