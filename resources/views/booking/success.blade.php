@extends('layouts.app')

@section('title', 'Booking Berhasil')

@section('content')
<div style="min-height:60vh; display:flex; align-items:center; justify-content:center; padding:48px 20px;">
    <div style="background:white; border-radius:20px; border:1px solid var(--border); padding:48px; max-width:520px; width:100%; text-align:center;">

        <div style="width:72px; height:72px; background:var(--light-green); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:36px; margin:0 auto 20px;">
            ✅
        </div>

        <h2 style="font-family:'Montserrat',sans-serif; font-size:26px; font-weight:800; color:var(--cerulean-dark); margin-bottom:10px;">
            Booking Diterima!
        </h2>
        <p style="font-size:14px; color:var(--muted); line-height:1.6; margin-bottom:28px;">
            Booking pelatihan Anda sedang menunggu verifikasi admin. Maksimal 1×24 jam setelah pembayaran dikonfirmasi, detail pelatihan akan dikirim ke email Anda.
        </p>

        <div style="background:var(--cream); border:1px solid var(--border); border-radius:10px; padding:16px; margin-bottom:16px;">
            <div style="font-size:11px; color:var(--muted); margin-bottom:4px;">Nomor Booking</div>
            <strong style="font-family:'Courier New',monospace; font-size:20px; color:var(--cerulean-dark);">
                {{ $booking->booking_number }}
            </strong>
        </div>

        <div style="background:var(--sky-pale); border:1px solid rgba(31,171,225,0.2); border-radius:10px; padding:16px; margin-bottom:24px; text-align:left;">
            <div style="font-size:12px; font-weight:700; color:var(--cerulean-dark); margin-bottom:10px;">Detail Kelas:</div>
            <div style="font-size:13px; color:var(--ink); display:flex; flex-direction:column; gap:4px;">
                <span><strong>{{ $booking->schedule->trainingClass->name }}</strong></span>
                <span style="color:var(--muted);">📅 {{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('l, d F Y') }}</span>
                <span style="color:var(--muted);">⏰ {{ substr($booking->schedule->start_time,0,5) }} – {{ substr($booking->schedule->end_time,0,5) }} WIB</span>
                <span style="color:var(--muted);">📍 {{ $booking->schedule->location }}</span>
            </div>
        </div>

        @if($booking->bankAccount)
        <div style="text-align:left; margin-bottom:24px;">
            <div style="font-size:13px; font-weight:700; color:var(--ink); margin-bottom:12px;">Instruksi Transfer:</div>
            <div style="display:flex; gap:10px; margin-bottom:8px; font-size:13px; color:var(--muted);">
                <div style="width:22px; height:22px; background:var(--cerulean); border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">1</div>
                <span>Transfer ke <strong style="color:var(--ink);">{{ $booking->bankAccount->bank_name }}</strong> — {{ $booking->bankAccount->account_number }} a.n. {{ $booking->bankAccount->account_holder }}</span>
            </div>
            <div style="display:flex; gap:10px; margin-bottom:8px; font-size:13px; color:var(--muted);">
                <div style="width:22px; height:22px; background:var(--cerulean); border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">2</div>
                <span>Nominal: <strong style="color:var(--cerulean-dark); font-family:'Courier New',monospace; font-size:15px;">Rp {{ number_format($booking->total, 0, ',', '.') }}</strong> (sudah termasuk kode unik)</span>
            </div>
            <div style="display:flex; gap:10px; font-size:13px; color:var(--muted);">
                <div style="width:22px; height:22px; background:var(--cerulean); border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">3</div>
                <span>Tunggu konfirmasi via email maks. 1×24 jam.</span>
            </div>
        </div>
        @endif

        <div style="display:flex; gap:10px;">
            <a href="{{ route('buyer.account', ['tab' => 'bookings']) }}" class="btn btn-outline" style="flex:1; justify-content:center;">Riwayat Booking</a>
            <a href="{{ route('home') }}" class="btn btn-primary" style="flex:1; justify-content:center;">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
