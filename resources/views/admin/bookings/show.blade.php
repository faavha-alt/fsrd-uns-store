@extends('layouts.admin')

@section('title', 'Detail Booking #' . $booking->booking_number)

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

    {{-- KIRI --}}
    <div>
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">Informasi Peserta</span>
                @php
                    $badges = [
                        'pending_verification' => ['class' => 'badge-yellow', 'label' => 'Menunggu Verifikasi'],
                        'confirmed' => ['class' => 'badge-blue', 'label' => 'Dikonfirmasi'],
                        'rejected' => ['class' => 'badge-red', 'label' => 'Ditolak'],
                        'completed' => ['class' => 'badge-green', 'label' => 'Selesai'],
                    ];
                    $badge = $badges[$booking->status] ?? ['class' => 'badge-gray', 'label' => $booking->status];
                @endphp
                <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
            </div>
            <div class="panel-body">
                <table style="width:100%; font-size:13px;">
                    <tr><td style="color:var(--muted); padding:6px 0; width:160px;">No. Booking</td><td><strong class="font-mono">{{ $booking->booking_number }}</strong></td></tr>
                    <tr><td style="color:var(--muted); padding:6px 0;">Nama Peserta</td><td><strong>{{ $booking->participant_name }}</strong></td></tr>
                    <tr><td style="color:var(--muted); padding:6px 0;">Email</td><td>{{ $booking->participant_email }}</td></tr>
                    <tr><td style="color:var(--muted); padding:6px 0;">Telepon</td><td>{{ $booking->participant_phone }}</td></tr>
                    @if($booking->institution)
                    <tr><td style="color:var(--muted); padding:6px 0;">Instansi</td><td>{{ $booking->institution }}</td></tr>
                    @endif
                    <tr><td style="color:var(--muted); padding:6px 0;">Tanggal Booking</td><td>{{ $booking->created_at->format('d M Y, H:i') }} WIB</td></tr>
                </table>
            </div>
        </div>

        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header"><span class="panel-title">Detail Kelas</span></div>
            <div class="panel-body">
                <table style="width:100%; font-size:13px;">
                    <tr><td style="color:var(--muted); padding:6px 0; width:160px;">Nama Kelas</td><td><strong>{{ $booking->schedule->trainingClass->name }}</strong></td></tr>
                    <tr><td style="color:var(--muted); padding:6px 0;">Tanggal</td><td>{{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('l, d F Y') }}</td></tr>
                    <tr><td style="color:var(--muted); padding:6px 0;">Waktu</td><td>{{ substr($booking->schedule->start_time,0,5) }} – {{ substr($booking->schedule->end_time,0,5) }} WIB</td></tr>
                    <tr><td style="color:var(--muted); padding:6px 0;">Lokasi</td><td>{{ $booking->schedule->location }}</td></tr>
                </table>
            </div>
        </div>

        @if($booking->payment_proof)
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Bukti Transfer</span></div>
            <div class="panel-body">
                @php $ext = pathinfo($booking->payment_proof, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ asset('storage/'.$booking->payment_proof) }}" style="max-width:100%; border-radius:8px; border:1px solid var(--border);">
                @else
                    <a href="{{ asset('storage/'.$booking->payment_proof) }}" target="_blank" class="btn btn-outline">📄 Lihat Bukti (PDF)</a>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- KANAN --}}
    <div>
        @if($booking->bankAccount)
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header"><span class="panel-title">Rekening Tujuan</span></div>
            <div class="panel-body">
                <strong style="font-size:14px; display:block;">{{ $booking->bankAccount->bank_name }}</strong>
                <span class="font-mono" style="font-size:16px;">{{ $booking->bankAccount->account_number }}</span>
                <span style="font-size:12px; color:var(--muted); display:block; margin-top:4px;">a.n. {{ $booking->bankAccount->account_holder }}</span>
            </div>
        </div>
        @endif

        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header"><span class="panel-title">Rincian Biaya</span></div>
            <div class="panel-body">
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
                    <span style="color:var(--muted);">Biaya Pelatihan</span>
                    <span>Rp {{ number_format($booking->schedule->trainingClass->price, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:12px;">
                    <span style="color:var(--muted);">Kode Unik</span>
                    <span>+{{ $booking->unique_code }}</span>
                </div>
                <div style="background:var(--gold-pale); border:1px solid rgba(233,168,40,0.3); border-radius:8px; padding:12px; text-align:center;">
                    <div style="font-size:11px; color:var(--muted);">Total Transfer</div>
                    <strong class="font-mono" style="font-size:20px; color:var(--cerulean-dark);">Rp {{ number_format($booking->total, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        @if($booking->status === 'pending_verification')
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header"><span class="panel-title">Verifikasi</span></div>
            <div class="panel-body">
                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" style="margin-bottom:10px;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%;"
                        onclick="return confirm('Konfirmasi booking ini?')">✓ Konfirmasi Booking</button>
                </form>
                <button type="button" class="btn btn-outline" style="width:100%; color:var(--red); border-color:var(--red);"
                    onclick="document.getElementById('rejectForm').style.display='block'; this.style.display='none'">
                    ✗ Tolak Booking
                </button>
                <div id="rejectForm" style="display:none; margin-top:12px;">
                    <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Alasan Penolakan</label>
                            <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger" style="width:100%;">Tolak & Kirim Alasan</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($booking->verifier)
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Diverifikasi Oleh</span></div>
            <div class="panel-body">
                <strong style="font-size:13px;">{{ $booking->verifier->name }}</strong>
                <span style="font-size:12px; color:var(--muted); display:block;">{{ $booking->updated_at->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>
        @endif
    </div>
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline">← Kembali ke Daftar Booking</a>
</div>
@endsection
