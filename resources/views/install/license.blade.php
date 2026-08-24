@extends('install.layout')

@php($step = 2)

@section('title', 'Aktivasi Lisensi')
@section('heading', 'Aktivasi Lisensi')
@section('subheading', 'Masukkan license key yang diberikan oleh penyedia layanan')

@section('content')
    @if ($unconfigured)
        <div class="alert-danger">
            Paket instalasi ini tidak dibekali konfigurasi license server yang valid, jadi instalasi <strong>tidak bisa dilanjutkan</strong>. Ini bukan bug yang perlu diperbaiki dengan mengedit <code>.env</code> sendiri — hubungi Hexa Sinergy Studio untuk mendapatkan paket instalasi resmi yang sudah terkonfigurasi dengan benar.
        </div>
    @else
        <form method="POST" action="{{ route('install.license.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">License Key</label>
                <input type="text" name="license_key" class="form-input" value="{{ old('license_key', $old) }}" placeholder="FSRD-XXXX-XXXX-XXXX" required autofocus autocomplete="off">
                <div class="form-hint">Key ini akan terikat ke domain <strong>{{ \App\Helpers\LicenseHelper::currentDomain() ?: request()->getHost() }}</strong>. Hubungi Hexa Sinergy Studio kalau belum punya key atau ingin memindahkan lisensi ke domain lain.</div>
            </div>

            <button type="submit" class="btn-submit">Aktivasi & Lanjutkan →</button>
        </form>
    @endif
    <a href="{{ route('install.welcome') }}" class="btn-back">← Kembali</a>
@endsection
