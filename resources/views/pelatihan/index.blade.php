@extends('layouts.app')

@section('title', 'Pelatihan')

@section('content')

{{-- HERO PELATIHAN --}}
<section class="hero" style="padding: 48px 40px 40px;">
    <div class="hero-eyebrow"><div class="hero-dot"></div><span>Program Pelatihan FSRD UNS</span></div>
    <h1 style="font-size: 36px;">Belajar dari <em>Ahlinya</em></h1>
    <p>Kelas seni dan desain langsung dari dosen & praktisi berpengalaman FSRD UNS.</p>
</section>

{{-- FILTER KATEGORI --}}
<div class="cat-bar">
    <a href="{{ route('pelatihan.index') }}" class="cat-btn {{ !request('category') ? 'active' : '' }}">Semua</a>
    @foreach($categories as $kat)
        <a href="{{ route('pelatihan.index', ['category' => $kat->slug]) }}"
           class="cat-btn {{ request('category') === $kat->slug ? 'active' : '' }}">
            {{ $kat->name }}
        </a>
    @endforeach
</div>

<section class="section">
    <div class="section-header">
        <div>
            <div class="section-title">Kelas Tersedia</div>
            <div class="section-sub">{{ $trainingClasses->total() }} kelas aktif</div>
        </div>
    </div>

    @if($trainingClasses->count() > 0)
    <div class="kelas-grid">
        @foreach($trainingClasses as $class)
        <a href="{{ route('pelatihan.show', $class) }}" class="kelas-card" style="text-decoration:none;color:inherit;">
            <div class="kelas-img" style="background: linear-gradient(135deg, var(--sky-pale), #BAE8F8)">
                @if($class->image)
                    <img src="{{ asset('storage/'.$class->image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <span>🎓</span>
                @endif
            </div>
            <div class="kelas-body">
                <div style="margin-bottom:6px;">
                    <span class="badge badge-blue">{{ $class->category->name }}</span>
                </div>
                <div class="kelas-title">{{ $class->name }}</div>
                <div class="kelas-meta">
                    <span>👤 {{ $class->instructor->name }} — {{ ucfirst($class->instructor->type) }}</span>
                    <span>📅 {{ $class->schedules->count() }} jadwal tersedia</span>
                </div>
                <div class="kelas-footer">
                    <div class="kelas-price">Rp {{ number_format($class->price, 0, ',', '.') }}</div>
                    @php $totalSlot = $class->schedules->sum(fn($s) => $s->remainingSlots()); @endphp
                    @if($totalSlot <= 0)
                        <span class="slot-badge slot-full">Penuh</span>
                    @elseif($totalSlot <= 5)
                        <span class="slot-badge slot-few">{{ $totalSlot }} slot</span>
                    @else
                        <span class="slot-badge slot-ok">{{ $totalSlot }} slot</span>
                    @endif
                </div>
                <button class="btn-booking">Lihat & Booking →</button>
            </div>
        </a>
        @endforeach
    </div>

    <div style="margin-top: 32px;">
        {{ $trainingClasses->withQueryString()->links() }}
    </div>
    @else
    <div style="text-align:center; padding: 60px 0; color: var(--muted);">
        <div style="font-size: 48px; margin-bottom: 16px;">🎓</div>
        <p style="font-size: 15px; font-weight: 600;">Belum ada kelas tersedia.</p>
    </div>
    @endif
</section>

@endsection
