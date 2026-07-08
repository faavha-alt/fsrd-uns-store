@extends('layouts.app')

@section('title', 'Kreator FSRD UNS')

@section('content')

{{-- HERO --}}
<section class="hero" style="padding:48px 40px 40px;">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-dot"></div>
            <span>Civitas Akademika FSRD UNS</span>
        </div>
        <h1 style="font-size:36px;">Kenali Para <em>Kreator</em></h1>
        <p>Dosen dan mahasiswa berbakat di balik karya-karya luar biasa FSRD UNS.</p>
    </div>
</section>

{{-- FILTER & SEARCH --}}
<div style="background:white; border-bottom:1px solid var(--border); padding:14px 40px;">
    <form method="GET" action="{{ route('creator.index') }}">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau jurusan..."
                style="flex:1; min-width:200px; padding:9px 14px; border:1.5px solid var(--border);
                       border-radius:8px; font-size:13px; font-family:'Poppins',sans-serif; outline:none;"
                onfocus="this.style.borderColor='var(--cerulean)'"
                onblur="this.style.borderColor='var(--border)'">

            <select name="type" onchange="this.form.submit()"
                style="padding:9px 14px; border:1.5px solid var(--border); border-radius:8px;
                       font-size:13px; font-family:'Poppins',sans-serif; outline:none; cursor:pointer;">
                <option value="">Semua</option>
                <option value="dosen" {{ request('type') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ request('type') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>

            <button type="submit" class="btn btn-primary">🔍 Cari</button>

            @if(request('search') || request('type'))
                <a href="{{ route('creator.index') }}" class="btn btn-outline">✕ Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- DAFTAR KREATOR --}}
<section class="section">
    <div class="section-header">
        <div>
            <div class="section-title">
                @if(request('type') === 'dosen') Dosen
                @elseif(request('type') === 'mahasiswa') Mahasiswa
                @else Semua Kreator
                @endif
            </div>
            <div class="section-sub">{{ $creators->total() }} kreator ditemukan</div>
        </div>
    </div>

    @if($creators->count() > 0)
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:18px;">
        @foreach($creators as $creator)
        <a href="{{ route('creator.show', $creator) }}"
           style="text-decoration:none; color:inherit;"
           class="product-card">
            <div style="padding:28px 20px; text-align:center; background:linear-gradient(135deg, var(--sky-pale), white);">
                @if($creator->photo)
                    <img src="{{ asset('storage/'.$creator->photo) }}"
                         style="width:80px; height:80px; border-radius:50%; object-fit:cover;
                                border:3px solid white; box-shadow:0 4px 12px rgba(14,125,167,0.2);
                                margin:0 auto 14px; display:block;">
                @else
                    <div style="width:80px; height:80px; border-radius:50%;
                        background:linear-gradient(135deg, var(--cerulean), var(--sky));
                        display:flex; align-items:center; justify-content:center;
                        font-family:'Montserrat',sans-serif; font-weight:900;
                        font-size:30px; color:white; margin:0 auto 14px;
                        box-shadow:0 4px 12px rgba(14,125,167,0.25);">
                        {{ substr($creator->name, 0, 1) }}
                    </div>
                @endif

                <span class="badge {{ $creator->type === 'dosen' ? 'badge-blue' : 'badge-green' }}"
                      style="margin-bottom:10px; display:inline-block;">
                    {{ ucfirst($creator->type) }}
                </span>

                <h3 style="font-family:'Montserrat',sans-serif; font-size:14px; font-weight:700;
                    color:var(--ink); margin-bottom:4px; line-height:1.3;">
                    {{ $creator->name }}
                </h3>

                @if($creator->department)
                <p style="font-size:11px; color:var(--muted); margin-bottom:12px;">
                    {{ $creator->department }}
                </p>
                @endif
            </div>

            <div style="padding:12px 16px; border-top:1px solid var(--border);
                display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:12px; color:var(--muted);">
                    {{ $creator->products_count }} karya
                </span>
                <span style="font-size:12px; color:var(--cerulean); font-weight:600;">
                    Lihat Profil →
                </span>
            </div>
        </a>
        @endforeach
    </div>

    <div style="margin-top:32px;">
        {{ $creators->withQueryString()->links() }}
    </div>

    @else
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
        <div style="font-size:48px; margin-bottom:16px;">👤</div>
        <p style="font-size:15px; font-weight:600; margin-bottom:8px;">Tidak ada kreator ditemukan.</p>
        <a href="{{ route('creator.index') }}" class="btn btn-primary">Lihat Semua Kreator</a>
    </div>
    @endif
</section>

@endsection
