@extends('layouts.app')

@section('title', 'Cara Pembelian')

@section('content')

{{-- HERO --}}
<section class="hero" style="padding:48px 40px 40px;">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-dot"></div>
            <span>Panduan Berbelanja</span>
        </div>
        <h1 style="font-size:36px;">Cara <em>Pembelian</em></h1>
        <p>Panduan lengkap berbelanja dan booking pelatihan di FSRD UNS Store.</p>
    </div>
</section>

{{-- TAB NAVIGATION --}}
<div style="background:white; border-bottom:1px solid var(--border); padding:0 40px; position:sticky; top:64px; z-index:90;">
    <div style="display:flex; gap:0;">
        <button onclick="showTab('produk')" id="tab-produk"
            style="padding:14px 24px; font-size:14px; font-weight:600; border:none; background:none; cursor:pointer;
                   border-bottom:2px solid var(--cerulean); color:var(--cerulean); font-family:'Poppins',sans-serif;">
            🛒 Beli Produk
        </button>
        <button onclick="showTab('pelatihan')" id="tab-pelatihan"
            style="padding:14px 24px; font-size:14px; font-weight:600; border:none; background:none; cursor:pointer;
                   border-bottom:2px solid transparent; color:var(--muted); font-family:'Poppins',sans-serif;">
            🎓 Booking Pelatihan
        </button>
    </div>
</div>

{{-- KONTEN PRODUK --}}
<div id="content-produk">
<section class="section">
    <div style="max-width:800px; margin:0 auto;">

        <div style="text-align:center; margin-bottom:40px;">
            <div class="section-title">Cara Membeli Produk</div>
            <div class="section-sub">Ikuti langkah-langkah berikut untuk berbelanja di Lapak FSRD UNS</div>
        </div>

        {{-- Step 1 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--cerulean); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                1
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Daftar & Login
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8; margin-bottom:12px;">
                    Buat akun buyer terlebih dahulu untuk bisa melakukan pembelian. Klik tombol <strong>Daftar</strong> di pojok kanan atas, isi data diri, lalu login.
                </p>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('buyer.register') }}" class="btn btn-primary btn-sm">Daftar Sekarang</a>
                    <a href="{{ route('buyer.login') }}" class="btn btn-outline btn-sm">Login</a>
                </div>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 2 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--cerulean); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                2
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Pilih Produk
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8; margin-bottom:12px;">
                    Jelajahi koleksi produk di halaman <strong>Lapak</strong>. Gunakan filter kategori atau kolom pencarian untuk menemukan produk yang kamu inginkan. Klik produk untuk melihat detail lengkap.
                </p>
                <a href="{{ route('lapak.index') }}" class="btn btn-outline btn-sm">Jelajahi Lapak →</a>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 3 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--cerulean); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                3
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Tambah ke Keranjang & Checkout
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    Klik <strong>Tambah ke Keranjang</strong> pada halaman detail produk. Setelah selesai memilih, buka keranjang dan klik <strong>Lanjut ke Checkout</strong>. Isi data pemesan dan pilih rekening tujuan transfer.
                </p>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 4 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--gold); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                4
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Transfer & Upload Bukti
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    Transfer sesuai nominal yang tertera <strong>termasuk kode unik</strong> (contoh: Rp 150.287 — jangan dibulatkan). Setelah transfer, upload foto/screenshot bukti transfer di halaman checkout.
                </p>
                <div style="background:var(--gold-pale); border:1px solid rgba(233,168,40,0.3); border-radius:8px; padding:12px 14px; margin-top:10px; font-size:13px; color:var(--cerulean-dark);">
                    ⚠️ <strong>Penting:</strong> Transfer TEPAT sesuai nominal termasuk kode unik 3 digit di akhir — ini membantu tim kami memverifikasi pembayaran Anda lebih cepat.
                </div>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 5 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--green); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                5
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Tunggu Konfirmasi
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    Tim FSRD UNS akan memverifikasi pembayaran dalam <strong>1×24 jam</strong>. Notifikasi konfirmasi akan dikirim ke email yang kamu daftarkan. Pantau status pesanan di menu <strong>Akun Saya → Pesanan</strong>.
                </p>
            </div>
        </div>

        {{-- FAQ --}}
        <div style="background:white; border-radius:14px; border:1px solid var(--border); padding:28px; margin-top:20px;">
            <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:20px;">
                ❓ Pertanyaan Umum (Produk)
            </h3>

            <div style="border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:16px;">
                <strong style="font-size:14px; color:var(--ink);">Apakah bisa membeli lebih dari 1 produk sekaligus?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Ya! Tambahkan beberapa produk ke keranjang, lalu checkout sekaligus dalam satu transaksi.
                </p>
            </div>

            <div style="border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:16px;">
                <strong style="font-size:14px; color:var(--ink);">Berapa lama proses verifikasi pembayaran?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Maksimal 1×24 jam pada hari kerja. Kami akan mengirimkan email konfirmasi setelah pembayaran diverifikasi.
                </p>
            </div>

            <div style="border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:16px;">
                <strong style="font-size:14px; color:var(--ink);">Apa yang terjadi jika pembayaran ditolak?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Kamu akan mendapat email berisi alasan penolakan. Biasanya karena nominal tidak sesuai atau bukti transfer tidak terbaca. Silakan hubungi kami via WhatsApp untuk bantuan lebih lanjut.
                </p>
            </div>

            <div>
                <strong style="font-size:14px; color:var(--ink);">Bagaimana cara menghubungi penjual/kreator?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Semua komunikasi melalui tim FSRD UNS Store. Hubungi kami via WhatsApp atau email yang tertera di footer halaman ini.
                </p>
            </div>
        </div>
    </div>
</section>
</div>

{{-- KONTEN PELATIHAN --}}
<div id="content-pelatihan" style="display:none;">
<section class="section">
    <div style="max-width:800px; margin:0 auto;">

        <div style="text-align:center; margin-bottom:40px;">
            <div class="section-title">Cara Booking Pelatihan</div>
            <div class="section-sub">Ikuti langkah-langkah berikut untuk mendaftar kelas pelatihan</div>
        </div>

        {{-- Step 1 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--cerulean); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                1
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Daftar & Login
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8; margin-bottom:12px;">
                    Buat akun buyer terlebih dahulu. Booking hanya bisa dilakukan setelah login.
                </p>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('buyer.register') }}" class="btn btn-primary btn-sm">Daftar Sekarang</a>
                    <a href="{{ route('buyer.login') }}" class="btn btn-outline btn-sm">Login</a>
                </div>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 2 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--cerulean); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                2
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Pilih Kelas & Jadwal
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8; margin-bottom:12px;">
                    Buka halaman <strong>Pelatihan</strong>, pilih kelas yang diminati. Di halaman detail kelas, pilih jadwal yang tersedia dan klik <strong>Booking Jadwal Ini</strong>.
                </p>
                <a href="{{ route('pelatihan.index') }}" class="btn btn-outline btn-sm">Lihat Pelatihan →</a>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 3 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--cerulean); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                3
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Isi Data Peserta
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    Lengkapi form pendaftaran: nama peserta, email, nomor telepon, dan asal instansi (opsional). Pilih rekening tujuan transfer, lalu upload bukti pembayaran.
                </p>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 4 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--gold); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                4
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Transfer Pembayaran
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    Transfer biaya pelatihan sesuai nominal yang tertera <strong>termasuk kode unik</strong>. Upload bukti transfer di form booking.
                </p>
                <div style="background:var(--gold-pale); border:1px solid rgba(233,168,40,0.3); border-radius:8px; padding:12px 14px; margin-top:10px; font-size:13px; color:var(--cerulean-dark);">
                    ⚠️ <strong>Penting:</strong> Transfer TEPAT sesuai nominal — kode unik membantu verifikasi lebih cepat.
                </div>
            </div>
        </div>

        <div style="border-left:2px dashed var(--border); margin-left:28px; height:24px;"></div>

        {{-- Step 5 --}}
        <div style="display:flex; gap:24px; margin-bottom:40px; align-items:flex-start;">
            <div style="flex-shrink:0; width:56px; height:56px; background:var(--green); border-radius:50%;
                display:flex; align-items:center; justify-content:center;
                font-family:'Montserrat',sans-serif; font-weight:900; font-size:22px; color:white;">
                5
            </div>
            <div style="flex:1;">
                <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:8px;">
                    Terima Konfirmasi & Bukti Booking
                </h3>
                <p style="font-size:14px; color:var(--muted); line-height:1.8;">
                    Setelah dikonfirmasi, kamu akan menerima email konfirmasi. Download <strong>Bukti Booking PDF</strong> dari menu <strong>Akun Saya → Booking Pelatihan</strong> dan tunjukkan kepada panitia saat hari-H.
                </p>
                <div style="background:var(--sky-pale); border:1px solid rgba(31,171,225,0.2); border-radius:8px; padding:12px 14px; margin-top:10px; font-size:13px; color:var(--cerulean-dark);">
                    💡 Hadir minimal <strong>15 menit sebelum</strong> kelas dimulai. Bawa bukti booking (cetak atau digital).
                </div>
            </div>
        </div>

        {{-- FAQ Pelatihan --}}
        <div style="background:white; border-radius:14px; border:1px solid var(--border); padding:28px; margin-top:20px;">
            <h3 style="font-family:'Montserrat',sans-serif; font-size:18px; font-weight:800; color:var(--cerulean-dark); margin-bottom:20px;">
                ❓ Pertanyaan Umum (Pelatihan)
            </h3>

            <div style="border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:16px;">
                <strong style="font-size:14px; color:var(--ink);">Apakah bisa membatalkan booking?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Pembatalan booking dapat dilakukan dengan menghubungi tim FSRD UNS Store via WhatsApp atau email sebelum jadwal kelas dimulai.
                </p>
            </div>

            <div style="border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:16px;">
                <strong style="font-size:14px; color:var(--ink);">Apakah ada sertifikat setelah mengikuti pelatihan?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Ya! Peserta yang hadir dan mengikuti kelas hingga selesai akan mendapatkan sertifikat dari FSRD UNS.
                </p>
            </div>

            <div style="border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:16px;">
                <strong style="font-size:14px; color:var(--ink);">Apakah bahan/alat disediakan?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Tergantung kelas. Detail bahan yang perlu dibawa atau yang sudah disediakan tercantum di deskripsi masing-masing kelas pelatihan.
                </p>
            </div>

            <div>
                <strong style="font-size:14px; color:var(--ink);">Berapa kapasitas peserta per kelas?</strong>
                <p style="font-size:13px; color:var(--muted); margin-top:6px; line-height:1.7;">
                    Kapasitas berbeda-beda per kelas dan ditampilkan sebagai "slot tersedia" di halaman detail kelas. Segera booking sebelum slot penuh!
                </p>
            </div>
        </div>

    </div>
</section>
</div>

{{-- CTA BOTTOM --}}
<section class="section section-alt">
    <div style="text-align:center; max-width:500px; margin:0 auto;">
        <div style="font-size:40px; margin-bottom:16px;">🤝</div>
        <h3 style="font-family:'Montserrat',sans-serif; font-size:22px; font-weight:800; color:var(--cerulean-dark); margin-bottom:10px;">
            Masih Ada Pertanyaan?
        </h3>
        <p style="font-size:14px; color:var(--muted); line-height:1.7; margin-bottom:24px;">
            Tim FSRD UNS Store siap membantu. Hubungi kami melalui WhatsApp atau email.
        </p>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
            @if(\App\Models\Setting::get('contact_wa'))
            <a href="https://wa.me/{{ \App\Models\Setting::get('contact_wa') }}" target="_blank"
               class="btn btn-primary" style="padding:12px 24px;">
                💬 Chat WhatsApp
            </a>
            @endif
            @if(\App\Models\Setting::get('contact_email'))
            <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}"
               class="btn btn-outline" style="padding:12px 24px;">
                ✉️ Kirim Email
            </a>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
function showTab(tab) {
    // Reset semua tab
    document.querySelectorAll('[id^="tab-"]').forEach(t => {
        t.style.borderBottomColor = 'transparent';
        t.style.color = 'var(--muted)';
    });
    document.querySelectorAll('[id^="content-"]').forEach(c => {
        c.style.display = 'none';
    });

    // Aktifkan tab yang dipilih
    document.getElementById('tab-' + tab).style.borderBottomColor = 'var(--cerulean)';
    document.getElementById('tab-' + tab).style.color = 'var(--cerulean)';
    document.getElementById('content-' + tab).style.display = 'block';
}
</script>
@endpush

@endsection
