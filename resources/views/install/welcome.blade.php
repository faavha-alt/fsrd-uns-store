@extends('install.layout')

@php($step = 1)

@section('title', 'Cek Server')
@section('heading', 'Cek Kesiapan Server')
@section('subheading', 'Pastikan semua syarat berikut terpenuhi sebelum lanjut')

@section('content')
    <ul class="check-list">
        @foreach ($checks as $name => $check)
            <li class="check-item">
                <div class="check-icon {{ $check['ok'] ? 'ok' : 'fail' }}">{{ $check['ok'] ? '✓' : '✕' }}</div>
                <div class="check-label">{{ $name }}</div>
                <div class="check-detail">{{ $check['label'] }}</div>
            </li>
        @endforeach
    </ul>

    @if ($ready)
        <a href="{{ route('install.license') }}" class="btn-submit" style="display:block; text-align:center; text-decoration:none; box-sizing:border-box;">Lanjutkan →</a>
    @else
        <div class="alert-danger">Ada syarat yang belum terpenuhi. Perbaiki dulu (hubungi hosting/admin server jika perlu), lalu muat ulang halaman ini.</div>
        <a href="{{ route('install.welcome') }}" class="btn-back">Muat ulang</a>
    @endif
@endsection
