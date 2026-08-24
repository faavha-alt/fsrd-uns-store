@extends('install.layout')

@section('title', 'Selesai')
@section('heading', 'Instalasi Selesai')
@section('subheading', 'Aplikasi sudah siap dipakai')

@section('content')
    <div class="success-icon">✓</div>
    <p style="text-align:center; font-size:13px; color:#475569; font-family:'Poppins',sans-serif; margin-bottom:24px;">
        Database sudah dimigrasi dan akun admin sudah dibuat. Silakan masuk ke panel admin untuk mulai mengatur situs.
    </p>
    <a href="{{ route('login') }}" class="btn-submit" style="display:block; text-align:center; text-decoration:none; box-sizing:border-box;">Masuk ke Panel Admin →</a>
    <a href="{{ route('home') }}" class="btn-back">Ke Beranda Situs</a>
@endsection
