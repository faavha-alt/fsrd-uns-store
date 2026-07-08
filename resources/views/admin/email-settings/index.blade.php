@extends('layouts.admin')

@section('title', 'Pengaturan Email')
@section('subtitle', 'Konfigurasi SMTP, notifikasi, dan Google OAuth')

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger" style="margin-bottom:20px;">{{ session('error') }}</div>
@endif

{{-- TAB NAVIGATION --}}
<div style="display:flex; gap:4px; border-bottom:2px solid var(--border); margin-bottom:24px;">
    <button onclick="showTab('smtp')" id="tab-smtp"
        style="padding:10px 20px; font-size:13px; font-weight:600; border:none; background:none; cursor:pointer;
               border-bottom:2px solid var(--cerulean); color:var(--cerulean); margin-bottom:-2px; font-family:'Poppins',sans-serif;">
        ⚙️ SMTP & Notifikasi
    </button>
    <button onclick="showTab('google')" id="tab-google"
        style="padding:10px 20px; font-size:13px; font-weight:600; border:none; background:none; cursor:pointer;
               border-bottom:2px solid transparent; color:var(--muted); margin-bottom:-2px; font-family:'Poppins',sans-serif;">
        🔐 Google OAuth
    </button>
</div>

{{-- ===== TAB SMTP ===== --}}
<div id="content-smtp">
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- Kolom Kiri --}}
    <div>
        <form action="{{ route('admin.email-settings.update') }}" method="POST">
        @csrf

        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">⚙️ Konfigurasi SMTP</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="form-label">Mail Host</label>
                    <input type="text" name="mail_host" class="form-control"
                        value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Port</label>
                        <input type="number" name="mail_port" class="form-control"
                            value="{{ $settings['mail_port'] ?? '587' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Enkripsi</label>
                        <select name="mail_encryption" class="form-control">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ ($settings['mail_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Username (Email Gmail)</label>
                    <input type="email" name="mail_username" class="form-control"
                        value="{{ $settings['mail_username'] ?? '' }}"
                        placeholder="emailkamu@gmail.com">
                </div>
                <div class="form-group">
                    <label class="form-label">App Password</label>
                    <input type="password" name="mail_password" class="form-control"
                        placeholder="Kosongkan jika tidak ingin mengubah">
                    <small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
                        Gunakan Google App Password (bukan password Gmail biasa).
                        <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color:var(--cerulean);">Generate di sini →</a>
                    </small>
                </div>
                <div class="form-group">
                    <label class="form-label">From Name</label>
                    <input type="text" name="mail_from_name" class="form-control"
                        value="{{ $settings['mail_from_name'] ?? 'FSRD UNS Store' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">From Address</label>
                    <input type="email" name="mail_from_address" class="form-control"
                        value="{{ $settings['mail_from_address'] ?? '' }}"
                        placeholder="noreply@fsrd.uns.ac.id">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Tujuan Notifikasi Admin</label>
                    <input type="email" name="notif_admin_email" class="form-control"
                        value="{{ $settings['notif_admin_email'] ?? '' }}"
                        placeholder="admin@fsrd.uns.ac.id">
                    <small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
                        Email ini yang akan menerima notifikasi order & booking baru.
                    </small>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">🔔 Pengaturan Notifikasi</span>
            </div>
            <div class="panel-body">
                <div style="font-size:12px; font-weight:700; color:var(--cerulean-dark); margin-bottom:12px; text-transform:uppercase; letter-spacing:0.06em;">
                    Notifikasi ke Admin
                </div>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                    <input type="checkbox" name="notif_admin_order_enabled" value="1"
                        {{ ($settings['notif_admin_order_enabled'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block;">Order Baru Masuk</strong>
                        <span style="font-size:11px; color:var(--muted);">Kirim notif ke admin setiap ada order baru</span>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:16px; cursor:pointer;">
                    <input type="checkbox" name="notif_admin_booking_enabled" value="1"
                        {{ ($settings['notif_admin_booking_enabled'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block;">Booking Baru Masuk</strong>
                        <span style="font-size:11px; color:var(--muted);">Kirim notif ke admin setiap ada booking baru</span>
                    </div>
                </label>

                <div style="border-top:1px solid var(--border); padding-top:14px; margin-bottom:12px;">
                    <div style="font-size:12px; font-weight:700; color:var(--cerulean-dark); margin-bottom:12px; text-transform:uppercase; letter-spacing:0.06em;">
                        Notifikasi ke Buyer
                    </div>
                </div>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                    <input type="checkbox" name="notif_buyer_order_confirmed" value="1"
                        {{ ($settings['notif_buyer_order_confirmed'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block;">Order Dikonfirmasi</strong>
                        <span style="font-size:11px; color:var(--muted);">Kirim email konfirmasi ke buyer</span>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                    <input type="checkbox" name="notif_buyer_order_rejected" value="1"
                        {{ ($settings['notif_buyer_order_rejected'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block;">Order Ditolak</strong>
                        <span style="font-size:11px; color:var(--muted);">Kirim email penolakan ke buyer</span>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; cursor:pointer;">
                    <input type="checkbox" name="notif_buyer_booking_confirmed" value="1"
                        {{ ($settings['notif_buyer_booking_confirmed'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block;">Booking Dikonfirmasi</strong>
                        <span style="font-size:11px; color:var(--muted);">Kirim email konfirmasi booking ke peserta</span>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="notif_buyer_booking_rejected" value="1"
                        {{ ($settings['notif_buyer_booking_rejected'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:16px; height:16px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block;">Booking Ditolak</strong>
                        <span style="font-size:11px; color:var(--muted);">Kirim email penolakan booking ke peserta</span>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; padding:13px;">
            💾 Simpan Pengaturan Email
        </button>
        </form>
    </div>

    {{-- Kolom Kanan --}}
    <div>
        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">🧪 Test Koneksi Email</span>
            </div>
            <div class="panel-body">
                <p style="font-size:13px; color:var(--muted); margin-bottom:16px; line-height:1.6;">
                    Kirim email percobaan untuk memastikan konfigurasi SMTP sudah benar.
                </p>
                <form action="{{ route('admin.email-settings.test') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Kirim Test ke Email</label>
                        <input type="email" name="test_email" class="form-control"
                            placeholder="emailkamu@gmail.com" required>
                    </div>
                    <button type="submit" class="btn btn-outline" style="width:100%;">
                        📤 Kirim Test Email
                    </button>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">📖 Panduan Gmail SMTP</span>
            </div>
            <div class="panel-body">
                <ol style="font-size:13px; color:var(--muted); line-height:1.8; padding-left:18px;">
                    <li>Buka <strong style="color:var(--ink);">myaccount.google.com</strong></li>
                    <li>Security → <strong style="color:var(--ink);">2-Step Verification</strong> → aktifkan</li>
                    <li>Cari <strong style="color:var(--ink);">App Passwords</strong></li>
                    <li>Generate untuk "Mail" → "Other: FSRD UNS"</li>
                    <li>Masukkan 16 karakter ke field App Password</li>
                </ol>
                <div style="background:var(--sky-pale); border-radius:8px; padding:12px; margin-top:16px; font-size:12px; color:var(--cerulean-dark);">
                    <strong>Rekomendasi:</strong><br>
                    Host: smtp.gmail.com · Port: 587 · Enkripsi: TLS
                </div>
            </div>
        </div>
    </div>

</div>
</div>

{{-- ===== TAB GOOGLE OAUTH ===== --}}
<div id="content-google" style="display:none;">
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- Kolom Kiri --}}
    <div>
        <form action="{{ route('admin.email-settings.google') }}" method="POST">
        @csrf

        <div class="panel" style="margin-bottom:16px;">
            <div class="panel-header">
                <span class="panel-title">🔐 Google OAuth Settings</span>
            </div>
            <div class="panel-body">

                <label style="display:flex; align-items:center; gap:10px; margin-bottom:20px; cursor:pointer; background:var(--sky-pale); padding:12px; border-radius:8px;">
                    <input type="checkbox" name="google_oauth_enabled" value="1"
                        {{ ($settings['google_oauth_enabled'] ?? '1') === '1' ? 'checked' : '' }}
                        style="width:18px; height:18px; accent-color:var(--cerulean);">
                    <div>
                        <strong style="font-size:13px; display:block; color:var(--cerulean-dark);">Aktifkan Login dengan Google</strong>
                        <span style="font-size:11px; color:var(--muted);">Tampilkan tombol Google di halaman login & register buyer</span>
                    </div>
                </label>

                <div class="form-group">
                    <label class="form-label">Google Client ID</label>
                    <input type="text" name="google_client_id" class="form-control"
                        value="{{ $settings['google_client_id'] ?? '' }}"
                        placeholder="xxxx.apps.googleusercontent.com">
                    <small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
                        Dari Google Cloud Console → Credentials → OAuth Client ID
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Google Client Secret</label>
                    <input type="password" name="google_client_secret" class="form-control"
                        value="{{ $settings['google_client_secret'] ?? '' }}"
                        placeholder="GOCSPX-xxxxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label class="form-label">Redirect URI</label>
                    <input type="url" name="google_redirect_uri" class="form-control"
                        value="{{ $settings['google_redirect_uri'] ?? url('/auth/google/callback') }}"
                        placeholder="https://yourdomain.com/auth/google/callback">
                    <small style="color:var(--muted); font-size:11px; margin-top:4px; display:block;">
                        ⚠️ URI ini harus didaftarkan di Google Cloud Console → Authorized redirect URIs
                    </small>
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; padding:13px;">
            💾 Simpan Google OAuth
        </button>
        </form>
    </div>

    {{-- Kolom Kanan --}}
    <div>
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">📖 Panduan Google OAuth</span>
            </div>
            <div class="panel-body">
                <ol style="font-size:13px; color:var(--muted); line-height:2; padding-left:18px;">
                    <li>Buka <strong style="color:var(--ink);">console.cloud.google.com</strong></li>
                    <li>Pilih project atau buat project baru</li>
                    <li>Buka <strong style="color:var(--ink);">APIs & Services → Credentials</strong></li>
                    <li>Klik <strong style="color:var(--ink);">+ Create Credentials → OAuth Client ID</strong></li>
                    <li>Application type: <strong style="color:var(--ink);">Web application</strong></li>
                    <li>Di <strong style="color:var(--ink);">Authorized redirect URIs</strong> tambahkan:<br>
                        <code style="background:var(--cream); padding:4px 8px; border-radius:4px; font-size:11px; display:block; margin-top:4px; word-break:break-all;">
                            {{ url('/auth/google/callback') }}
                        </code>
                    </li>
                    <li>Klik <strong style="color:var(--ink);">Create</strong> → copy Client ID & Secret ke form kiri</li>
                </ol>

                <div style="background:#FEF9C3; border:1px solid #FCD34D; border-radius:8px; padding:12px; margin-top:16px; font-size:12px; color:#92400E;">
                    ⚠️ <strong>Penting saat pindah server/domain:</strong><br>
                    Update Redirect URI di Google Cloud Console agar sesuai domain baru, lalu update di form ini dan simpan.
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
function showTab(tab) {
    ['smtp', 'google'].forEach(function(t) {
        document.getElementById('tab-' + t).style.borderBottomColor = 'transparent';
        document.getElementById('tab-' + t).style.color = 'var(--muted)';
        document.getElementById('content-' + t).style.display = 'none';
    });
    document.getElementById('tab-' + tab).style.borderBottomColor = 'var(--cerulean)';
    document.getElementById('tab-' + tab).style.color = 'var(--cerulean)';
    document.getElementById('content-' + tab).style.display = 'block';
}

// Auto buka tab Google kalau dari redirect
if (window.location.hash === '#tab-google') {
    showTab('google');
}
</script>
@endpush

@endsection