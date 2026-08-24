@extends('install.layout')

@php($step = 5)

@section('title', 'Konfirmasi')
@section('heading', 'Konfirmasi Instalasi')
@section('subheading', 'Cek kembali sebelum instalasi dijalankan')

@section('content')
    @if ($errors->has('install'))
        <div class="alert-danger">{{ $errors->first('install') }}</div>
    @endif

    <table class="summary-table">
        @if (session('install.license_key'))
            <tr><td>License Key</td><td>{{ session('install.license_key') }}</td></tr>
        @endif
        <tr><td>Nama Situs</td><td>{{ $account['site_name'] }}</td></tr>
        <tr><td>Database Host</td><td>{{ $db['db_host'] }}:{{ $db['db_port'] }}</td></tr>
        <tr><td>Nama Database</td><td>{{ $db['db_database'] }}</td></tr>
        <tr><td>Username DB</td><td>{{ $db['db_username'] }}</td></tr>
        <tr><td>Nama Admin</td><td>{{ $account['admin_name'] }}</td></tr>
        <tr><td>Email Admin</td><td>{{ $account['admin_email'] }}</td></tr>
    </table>

    <div class="form-hint" style="margin-bottom:16px;">
        Menekan tombol di bawah akan menulis ulang <code>.env</code>, menjalankan migrasi database, dan membuat akun admin di atas. Proses ini hanya bisa dijalankan sekali.
    </div>

    <form method="POST" action="{{ route('install.run') }}">
        @csrf
        <button type="submit" class="btn-submit">Install Sekarang</button>
    </form>
    <a href="{{ route('install.account') }}" class="btn-back">← Kembali</a>
@endsection
