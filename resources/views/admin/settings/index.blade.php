@extends('layouts.admin')

@section('title', 'Pengaturan Site')
@section('subtitle', 'Kelola tampilan dan informasi platform')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- KOLOM KIRI --}}
    <div>

        {{-- Identitas Site --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <span class="panel-title">🏪 Identitas Site</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="form-label">Nama Site</label>
                    <input type="text" name="site_name" class="form-control"
                        value="{{ $settings['site_name'] ?? 'FSRD UNS Store' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="site_tagline" class="form-control"
                        value="{{ $settings['site_tagline'] ?? '' }}"
                        placeholder="Seni Rupa & Desain UNS">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Site</label>
                    <textarea name="site_description" class="form-control" rows="3"
                        placeholder="Deskripsi singkat platform...">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>

                {{-- Logo --}}
                <div class="form-group">
                    <label class="form-label">Logo Site</label>
                    @if(!empty($settings['site_logo']))
                        <div style="margin-bottom:10px;">
                            <img src="{{ asset('storage/'.$settings['site_logo']) }}"
                                style="height:50px; object-fit:contain; background:var(--cerulean); padding:6px; border-radius:8px;">
                        </div>
                    @endif
                    <input type="file" name="site_logo" class="form-control" accept="image/*">
                    <small style="color:var(--muted); font-size:11px;">Format: PNG/SVG transparan. Maks 2MB.</small>
                </div>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <span class="panel-title">🖼️ Hero / Banner Utama</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="form-label">Judul Hero</label>
                    <input type="text" name="hero_title" class="form-control"
                        value="{{ $settings['hero_title'] ?? '' }}"
                        placeholder="Karya Terbaik, Tersedia untuk Dunia">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul Hero</label>
                    <textarea name="hero_subtitle" class="form-control" rows="2"
                        placeholder="Deskripsi singkat di bawah judul hero...">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                </div>

                {{-- Hero Image --}}
                <div class="form-group">
                    <label class="form-label">Gambar Background Hero</label>
                    @if(!empty($settings['hero_image']))
                        <div style="margin-bottom:10px;">
                            <img src="{{ asset('storage/'.$settings['hero_image']) }}"
                                style="width:100%; height:120px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                            <small style="color:var(--muted); font-size:11px; display:block; margin-top:4px;">Gambar hero saat ini</small>
                        </div>
                    @endif
                    <input type="file" name="hero_image" class="form-control" accept="image/*">
                    <small style="color:var(--muted); font-size:11px;">Rekomendasi: 1920×600px. Maks 5MB.</small>
                </div>
            </div>
        </div>
{{-- Tentang --}}
<div class="panel" style="margin-bottom:20px;">
    <div class="panel-header">
        <span class="panel-title">📖 Halaman Tentang</span>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="form-label">Judul Halaman</label>
            <input type="text" name="about_title" class="form-control"
                value="{{ $settings['about_title'] ?? 'Tentang FSRD UNS Store' }}">
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi Singkat</label>
            <textarea name="about_description" class="form-control" rows="3">{{ $settings['about_description'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Sejarah Singkat</label>
            <textarea name="about_history" class="form-control" rows="4">{{ $settings['about_history'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Visi</label>
            <textarea name="about_vision" class="form-control" rows="3">{{ $settings['about_vision'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Misi (gunakan angka 1. 2. 3. untuk poin)</label>
            <textarea name="about_mission" class="form-control" rows="5">{{ $settings['about_mission'] ?? '' }}</textarea>
        </div>
    </div>
</div>
    </div>

    {{-- KOLOM KANAN --}}
    <div>

        {{-- Kontak --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <span class="panel-title">📞 Informasi Kontak</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="form-label">Email Kontak</label>
                    <input type="email" name="contact_email" class="form-control"
                        value="{{ $settings['contact_email'] ?? '' }}"
                        placeholder="fsrd@uns.ac.id">
                </div>
                <div class="form-group">
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" name="contact_wa" class="form-control"
                        value="{{ $settings['contact_wa'] ?? '' }}"
                        placeholder="628123456789 (format internasional)">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea name="contact_address" class="form-control" rows="3"
                        placeholder="Jl. Ir. Sutami No.36A, Kentingan, Surakarta">{{ $settings['contact_address'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-header">
                <span class="panel-title">📱 Social Media</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control"
                        value="{{ $settings['instagram_url'] ?? '' }}"
                        placeholder="https://instagram.com/fsrduns">
                </div>
                <div class="form-group">
                    <label class="form-label">YouTube URL</label>
                    <input type="url" name="youtube_url" class="form-control"
                        value="{{ $settings['youtube_url'] ?? '' }}"
                        placeholder="https://youtube.com/@fsrduns">
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control"
                        value="{{ $settings['facebook_url'] ?? '' }}"
                        placeholder="https://facebook.com/fsrduns">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter/X URL</label>
                    <input type="url" name="twitter_url" class="form-control"
                        value="{{ $settings['twitter_url'] ?? '' }}"
                        placeholder="https://twitter.com/fsrduns">
                </div>
            </div>
        </div>

    </div>
</div>

{{-- SAVE BUTTON --}}
<div style="position:sticky; bottom:0; background:white; border-top:1px solid var(--border); padding:16px 0; margin-top:8px; display:flex; gap:10px;">
    <button type="submit" class="btn btn-primary" style="padding:12px 32px; font-size:14px;">
        💾 Simpan Semua Pengaturan
    </button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Batal</a>
</div>

</form>
@endsection
