<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Booking — {{ $booking->booking_number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #1A1A2E; background: white; }

        /* Header */
        .header { background: #0E7DA7; color: white; padding: 24px 32px; }
        .header-inner { display: table; width: 100%; }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 200px; }
        .header h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .header p  { font-size: 11px; opacity: 0.75; }
        .status-badge {
            display: inline-block; background: #E9A828; color: white;
            padding: 5px 14px; border-radius: 20px;
            font-size: 11px; font-weight: 700; text-transform: uppercase;
        }
        .booking-num { font-size: 16px; font-weight: 700; margin-top: 6px; font-family: 'Courier New', monospace; }

        /* Body */
        .body { padding: 28px 32px; }

        /* Title */
        .doc-title { text-align: center; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 2px solid #E5E7EB; }
        .doc-title h2 { font-size: 17px; font-weight: 700; color: #0E7DA7; margin-bottom: 4px; }
        .doc-title p  { font-size: 12px; color: #6B7280; }

        /* Class box */
        .class-box {
            background: #E6F7FD; border: 1px solid #1FABE1;
            border-radius: 8px; padding: 18px; margin-bottom: 20px;
        }
        .class-name { font-size: 17px; font-weight: 700; color: #0E7DA7; margin-bottom: 12px; }
        .meta-table  { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 3px 0; font-size: 12px; vertical-align: top; }
        .meta-icon { width: 22px; }

        /* Info grid */
        .info-grid { display: table; width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 12px 0; }
        .info-col  { display: table-cell; width: 50%; vertical-align: top; }

        /* Section */
        .section { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px; }
        .section-title {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #6B7280;
            margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #E5E7EB;
        }
        .info-row   { display: table; width: 100%; margin-bottom: 7px; }
        .info-label { display: table-cell; font-size: 11px; color: #6B7280; width: 110px; vertical-align: top; padding-top: 1px; }
        .info-value { display: table-cell; font-size: 12px; font-weight: 600; color: #1A1A2E; }

        /* Payment */
        .payment-box {
            background: #FEF7E7; border: 1px solid #E9A828;
            border-radius: 8px; padding: 16px; text-align: center; margin-bottom: 18px;
        }
        .payment-label  { font-size: 11px; color: #6B7280; margin-bottom: 4px; }
        .payment-amount { font-size: 26px; font-weight: 700; color: #0A5F80; font-family: 'DejaVu Sans Mono', monospace; }
        .payment-note   { font-size: 11px; color: #6B7280; margin-top: 4px; }

        /* Note */
        .note-box {
            background: #F0FDF4; border: 1px solid #6EE7B7;
            border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;
        }
        .note-box p { font-size: 12px; color: #065F46; line-height: 1.6; }

        /* Signature */
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 24px; padding-top: 20px; border-top: 1px solid #E5E7EB; }
        .sig-left  { width: 50%; vertical-align: top; }
        .sig-right { width: 50%; vertical-align: top; text-align: right; }
        .sig-title { font-size: 11px; color: #6B7280; margin-bottom: 44px; }
        .sig-line  { border-top: 1px solid #1A1A2E; padding-top: 6px; font-size: 12px; font-weight: 600; display: inline-block; min-width: 160px; }

        /* Footer */
        .page-footer {
            margin-top: 24px; padding-top: 12px;
            border-top: 1px solid #E5E7EB;
            text-align: center; font-size: 10px; color: #9CA3AF;
        }

        /* Watermark */
        .watermark {
            position: fixed; top: 45%; left: 20%;
            font-size: 72px; font-weight: 900;
            color: rgba(14,125,167,0.05);
            text-transform: uppercase; letter-spacing: 0.1em;
            transform: rotate(-30deg);
        }
    </style>
</head>
<body>

<div class="watermark">VALID</div>

{{-- HEADER --}}
<div class="header">
    <div class="header-inner">
        <div class="header-left">
            <h1>FSRD UNS Store</h1>
            <p>Fakultas Seni Rupa & Desain — Universitas Sebelas Maret</p>
        </div>
        <div class="header-right">
            <div class="status-badge">✓ Dikonfirmasi</div>
            <div class="booking-num">{{ $booking->booking_number }}</div>
        </div>
    </div>
</div>

{{-- BODY --}}
<div class="body">

    <div class="doc-title">
        <h2>BUKTI BOOKING PELATIHAN</h2>
        <p>Dokumen ini merupakan bukti resmi pendaftaran peserta pelatihan FSRD UNS</p>
    </div>

    {{-- Detail Kelas --}}
    <div class="class-box">
        <div class="class-name">{{ $booking->schedule->trainingClass->name }}</div>
        <table class="meta-table">
            <tr>
                <td class="meta-icon">📅</td>
                <td>{{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="meta-icon">⏰</td>
                <td>{{ substr($booking->schedule->start_time, 0, 5) }} – {{ substr($booking->schedule->end_time, 0, 5) }} WIB</td>
            </tr>
            <tr>
                <td class="meta-icon">📍</td>
                <td>{{ $booking->schedule->location }}</td>
            </tr>
            <tr>
                <td class="meta-icon">👤</td>
                <td>Instruktur: {{ $booking->schedule->trainingClass->instructor->name }}</td>
            </tr>
            <tr>
                <td class="meta-icon">🏷️</td>
                <td>Kategori: {{ $booking->schedule->trainingClass->category->name }}</td>
            </tr>
        </table>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-col">
            <div class="section">
                <div class="section-title">Data Peserta</div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $booking->participant_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $booking->participant_email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $booking->participant_phone }}</div>
                </div>
                @if($booking->institution)
                <div class="info-row">
                    <div class="info-label">Instansi</div>
                    <div class="info-value">{{ $booking->institution }}</div>
                </div>
                @endif
            </div>
        </div>
        <div class="info-col">
            <div class="section">
                <div class="section-title">Info Booking</div>
                <div class="info-row">
                    <div class="info-label">No. Booking</div>
                    <div class="info-value" style="font-family:'Courier New',monospace; font-size:11px;">{{ $booking->booking_number }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tgl. Daftar</div>
                    <div class="info-value">{{ $booking->created_at->format('d M Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value" style="color:#059669; font-weight:700;">✓ Dikonfirmasi</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rekening</div>
                    <div class="info-value">{{ $booking->bankAccount->bank_name ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment --}}
    <div class="payment-box">
        <div class="payment-label">Total Pembayaran</div>
        <div class="payment-amount">Rp {{ number_format($booking->total, 0, ',', '.') }}</div>
        <div class="payment-note">Sudah lunas & terverifikasi oleh Admin FSRD UNS</div>
    </div>

    {{-- Note --}}
    <div class="note-box">
        <p>
            <strong>Petunjuk Kehadiran:</strong>
            Tunjukkan dokumen ini (cetak atau digital) kepada panitia saat registrasi ulang di lokasi pelatihan.
            Hadir minimal 15 menit sebelum waktu mulai.
            Bawa alat tulis dan perlengkapan sesuai kebutuhan kelas.
        </p>
    </div>

    {{-- Signature --}}
    <table class="sig-table">
        <tr>
            <td class="sig-left">
                <div class="sig-title">Peserta,</div>
                <span class="sig-line">{{ $booking->participant_name }}</span>
            </td>
            <td class="sig-right">
                <div class="sig-title">Pengelola FSRD UNS Store,</div>
                <span class="sig-line">Fakultas Seni Rupa & Desain UNS</span>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <div class="page-footer">
        Diterbitkan oleh FSRD UNS Store — {{ now()->format('d M Y, H:i') }} WIB —
        Dokumen ini sah tanpa tanda tangan basah
    </div>

</div>
</body>
</html>
