@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="detail-container">
    <h1 style="font-family:'Montserrat',sans-serif; font-size:26px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">Riwayat Booking Pelatihan</h1>
    <p style="font-size:13px; color:var(--muted); margin-bottom:28px;">Halo, <strong>{{ auth()->user()->name }}</strong> — berikut daftar booking pelatihan Anda.</p>

    @if($bookings->count() > 0)
    <div style="display:flex; flex-direction:column; gap:12px;">
        @foreach($bookings as $booking)
        <div style="background:white; border-radius:12px; border:1px solid var(--border); padding:18px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div>
                    <strong class="font-mono" style="font-size:14px; color:var(--cerulean-dark);">{{ $booking->booking_number }}</strong>
                    <span style="font-size:12px; color:var(--muted); display:block; margin-top:2px;">
                        {{ $booking->created_at->format('d M Y, H:i') }} WIB
                    </span>
                </div>
                @php
                    $badges = [
                        'pending_payment' => ['class' => 'badge-gray', 'label' => 'Belum Bayar'],
                        'pending_verification' => ['class' => 'badge-yellow', 'label' => 'Menunggu Verifikasi'],
                        'confirmed' => ['class' => 'badge-blue', 'label' => 'Dikonfirmasi'],
                        'rejected' => ['class' => 'badge-red', 'label' => 'Ditolak'],
                        'completed' => ['class' => 'badge-green', 'label' => 'Selesai'],
                    ];
                    $badge = $badges[$booking->status] ?? ['class' => 'badge-gray', 'label' => $booking->status];
                @endphp
                <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
            </div>

            <div style="border-top:1px solid var(--border); padding-top:12px; margin-bottom:12px;">
                <strong style="font-size:14px; display:block; margin-bottom:4px;">
                    {{ $booking->schedule->trainingClass->name }}
                </strong>
                <span style="font-size:12px; color:var(--muted); display:block;">
                    📅 {{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('l, d F Y') }}
                </span>
                <span style="font-size:12px; color:var(--muted); display:block;">
                    ⏰ {{ substr($booking->schedule->start_time,0,5) }} – {{ substr($booking->schedule->end_time,0,5) }} WIB
                </span>
                <span style="font-size:12px; color:var(--muted); display:block;">
                    📍 {{ $booking->schedule->location }}
                </span>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:12px;">
                <div>
                    <span style="font-size:12px; color:var(--muted);">Total Transfer</span>
                    <strong style="font-family:'Montserrat',sans-serif; font-size:16px; color:var(--cerulean-dark); display:block;">
                        Rp {{ number_format($booking->total, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            @if($booking->status === 'rejected' && $booking->rejection_reason)
            <div style="margin-top:12px; padding:10px; background:var(--light-red); border-radius:8px; font-size:12px; color:var(--red);">
                <strong>Alasan penolakan:</strong> {{ $booking->rejection_reason }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    <div style="margin-top:24px;">{{ $bookings->links() }}</div>
    @else
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
        <div style="font-size:48px; margin-bottom:16px;">🎓</div>
        <p style="font-size:15px; font-weight:600; margin-bottom:8px;">Belum ada booking pelatihan.</p>
        <p style="font-size:13px; margin-bottom:24px;">Yuk ikuti kelas pelatihan di FSRD UNS!</p>
        <a href="{{ route('pelatihan.index') }}" class="btn btn-primary">Lihat Pelatihan</a>
    </div>
    @endif
</div>
@endsection
