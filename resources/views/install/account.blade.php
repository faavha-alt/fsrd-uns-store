@extends('install.layout')

@php($step = 4)

@section('title', 'Akun Admin')
@section('heading', 'Situs & Akun Admin')
@section('subheading', 'Data ini dipakai untuk login pertama kali sebagai Admin')

@section('content')
    <form method="POST" action="{{ route('install.account.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Situs</label>
            <input type="text" name="site_name" class="form-input" value="{{ old('site_name', $old['site_name'] ?? 'FSRD UNS Store') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Nama Admin</label>
            <input type="text" name="admin_name" class="form-input" value="{{ old('admin_name', $old['admin_name'] ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Email Admin</label>
            <input type="email" name="admin_email" class="form-input" value="{{ old('admin_email', $old['admin_email'] ?? '') }}" required>
            <div class="form-hint">Dipakai untuk login ke /management-fsrd/masuk</div>
        </div>
        <div class="form-group">
            <label class="form-label">Password Admin</label>
            <input type="password" name="admin_password" class="form-input" required minlength="8">
        </div>
        <div class="form-group">
            <label class="form-label">Ulangi Password</label>
            <input type="password" name="admin_password_confirmation" class="form-input" required minlength="8">
        </div>

        <button type="submit" class="btn-submit">Lanjutkan →</button>
    </form>
    <a href="{{ route('install.database') }}" class="btn-back">← Kembali</a>
@endsection
