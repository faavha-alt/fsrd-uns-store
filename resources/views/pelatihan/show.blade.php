@extends('layouts.app')

@section('title', $trainingClass->name)

@section('content')
<div class="detail-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('pelatihan.index') }}">Pelatihan</a>
        <span class="breadcrumb-sep">›</span>
        <span style="color: var(--ink);">{{ $trainingClass->name }}</span>
    </div>

    <div class="detail-grid">
        {{-- GAMBAR --}}
        <div>
            <div class="main-image">
                @if($trainingClass->image)
                    <img src="{{ asset('storage/'.$trainingClass->image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span>🎓</span>
                @endif
            </div>

            {{-- DESKRIPSI --}}
            @if($trainingClass->description)
            <div style="margin-top: 24px; padding: 20px; background: white; border-radius: 12px; border: 1px solid var(--border);">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); margin-bottom:10px;">Deskripsi Kelas</div>
                <p style="font-size:13px; line-height:1.7; color:var(--ink);">{{ $trainingClass->description }}</p>
            </div>
            @endif

            {{-- SILABUS --}}
            @if($trainingClass->syllabus)
            <div style="margin-top: 16px; padding: 20px; background: white; border-radius: 12px; border: 1px solid var(--border);">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); margin-bottom:10px;">Silabus</div>
                <p style="font-size:13px; line-height:1.7; color:var(--ink);">{{ $trainingClass->syllabus }}</p>
            </div>
            @endif
        </div>

        {{-- INFO BOOKING --}}
        <div class="detail-info">
            <span class="badge badge-blue" style="margin-bottom:12px;">{{ $trainingClass->category->name }}</span>

            <h1 class="detail-title">{{ $trainingClass->name }}</h1>

            <div class="detail-seller-card">
                <div class="detail-seller-avatar">{{ substr($trainingClass->instructor->name, 0, 1) }}</div>
                <div class="detail-seller-info">
                    <strong>{{ $trainingClass->instructor->name }}</strong>
                    <span>{{ ucfirst($trainingClass->instructor->type) }} · {{ $trainingClass->instructor->department ?? 'FSRD UNS' }}</span>
                </div>
            </div>

            <div class="detail-price-box">
                <div class="detail-price">Rp {{ number_format($trainingClass->price, 0, ',', '.') }}</div>
                <div class="detail-price-note">Per peserta — termasuk bahan & sertifikat</div>
            </div>

            {{-- JADWAL TERSEDIA --}}
            <div style="margin-bottom: 16px;">
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); margin-bottom:10px;">Pilih Jadwal</div>
                @forelse($trainingClass->schedules as $schedule)
                <div style="padding: 14px; border: 2px solid {{ $schedule->isFull() ? 'var(--border)' : 'var(--cerulean)' }}; border-radius: 10px; margin-bottom: 8px; {{ $schedule->isFull() ? 'opacity:0.6' : '' }}">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong style="font-size:13px; display:block;">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</strong>
                            <span style="font-size:12px; color:var(--muted);">{{ substr($schedule->start_time,0,5) }} – {{ substr($schedule->end_time,0,5) }} WIB</span>
                            <span style="font-size:12px; color:var(--muted); display:block;">📍 {{ $schedule->location }}</span>
                        </div>
                        @if($schedule->isFull())
                            <span class="slot-badge slot-full">Penuh</span>
                        @elseif($schedule->remainingSlots() <= 3)
                            <span class="slot-badge slot-few">{{ $schedule->remainingSlots() }} slot</span>
                        @else
                            <span class="slot-badge slot-ok">{{ $schedule->remainingSlots() }} slot</span>
                        @endif
                    </div>
                    @if(!$schedule->isFull())
                    <a href="{{ route('booking.create', $schedule) }}" class="btn-booking" style="margin-top:10px; display:block; text-align:center; text-decoration:none;">
    Booking Jadwal Ini
</a>
                    @endif
                </div>
                @empty
                <p style="font-size:13px; color:var(--muted);">Belum ada jadwal tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
