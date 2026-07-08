@extends('layouts.app')

@section('title', 'Pesanan Diterima')

@section('content')
<div style="min-height:60vh; display:flex; align-items:center; justify-content:center; padding:48px 20px;">
    <div style="background:white; border-radius:20px; border:1px solid var(--border); padding:48px; max-width:500px; width:100%; text-align:center; box-shadow:0 8px 40px rgba(0,0,0,0.06);">

        <div style="width:72px; height:72px; background:var(--light-green); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:36px; margin:0 auto 20px;">
            ✅
        </div>

        <h2 style="font-family:'Montserrat',sans-serif; font-size:26px; font-weight:800; color:var(--cerulean-dark); margin-bottom:10px;">
            Pesanan Diterima!
        </h2>
        <p style="font-size:14px; color:var(--muted); line-height:1.6; margin-bottom:28px;">
            Terima kasih! Pesanan Anda sedang menunggu verifikasi pembayaran oleh admin. Proses verifikasi maks. 1×24 jam.
        </p>

        <div style="background:var(--cream); border:1px solid var(--border); border-radius:10px; padding:16px; margin-bottom:24px;">
            <div style="font-size:11px; color:var(--muted); margin-bottom:4px;">Nomor Order Anda</div>
            <strong style="font-family:'Courier New',monospace; font-size:20px; color:var(--cerulean-dark);">
                {{ $order->order_number }}
            </strong>
        </div>

        {{-- Langkah selanjutnya --}}
        <div style="text-align:left; margin-bottom:28px;">
            <div style="font-size:13px; font-weight:700; color:var(--ink); margin-bottom:12px;">Langkah Selanjutnya:</div>
            @php $bank = $order->bankAccount; @endphp
            @if($bank)
            <div style="display:flex; gap:10px; margin-bottom:8px; font-size:13px; color:var(--muted);">
                <div style="width:22px; height:22px; background:var(--cerulean); border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">1</div>
                <span>Transfer ke <strong style="color:var(--ink);">{{ $bank->bank_name }}</strong> — {{ $bank->account_number }} a.n. {{ $bank->account_holder }}</span>
            </div>
            @endif
            <div style="display:flex; gap:10px; margin-bottom:8px; font-size:13px; color:var(--muted);">
                <div style="width:22px; height:22px; background:var(--cerulean); border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">2</div>
                <span>Pastikan nominal tepat: <strong style="color:var(--cerulean-dark); font-family:'Courier New',monospace;">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></span>
            </div>
            <div style="display:flex; gap:10px; font-size:13px; color:var(--muted);">
                <div style="width:22px; height:22px; background:var(--cerulean); border-radius:50%; color:white; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; flex-shrink:0;">3</div>
                <span>Tunggu konfirmasi via email maks. 1×24 jam setelah transfer.</span>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn-beli" style="display:block; text-align:center; text-decoration:none;">
            ← Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
