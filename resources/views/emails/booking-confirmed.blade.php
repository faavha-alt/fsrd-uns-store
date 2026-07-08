<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #F0F4F8; color: #1A1A2E; font-size: 14px; }
        .wrap { max-width: 580px; margin: 32px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .header { background: #059669; padding: 28px 32px; text-align: center; }
        .header h1 { color: white; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .body { padding: 28px 32px; }
        .class-box { background: #E6F7FD; border: 1px solid #1FABE1; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        .class-box h3 { font-size: 16px; color: #0E7DA7; margin-bottom: 10px; }
        .class-box p { font-size: 13px; color: #0A5F80; margin-bottom: 6px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 10px 0; border-bottom: 1px solid #F1F5F9; font-size: 13px; }
        .info-table td:first-child { color: #6B7280; width: 140px; }
        .info-table td:last-child { font-weight: 600; }
        .note-box { background: #F0FDF4; border: 1px solid #6EE7B7; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; font-size: 13px; color: #065F46; }
        .footer { background: #F8FAFC; padding: 20px 32px; text-align: center; font-size: 12px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <span style="font-size:48px; display:block; margin-bottom:12px;">✅</span>
        <h1>Booking Dikonfirmasi!</h1>
        <p>Selamat, pendaftaran pelatihan Anda berhasil</p>
    </div>
    <div class="body">
        <p style="font-size:14px; margin-bottom:16px;">Halo, <strong>{{ $booking->participant_name }}</strong>!</p>

        <div class="class-box">
            <h3>{{ $booking->schedule->trainingClass->name }}</h3>
            <p>📅 {{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('l, d F Y') }}</p>
            <p>⏰ {{ substr($booking->schedule->start_time,0,5) }} – {{ substr($booking->schedule->end_time,0,5) }} WIB</p>
            <p>📍 {{ $booking->schedule->location }}</p>
            <p>👤 {{ $booking->schedule->trainingClass->instructor->name }}</p>
        </div>

        <table class="info-table">
            <tr><td>No. Booking</td><td style="font-family:monospace;">{{ $booking->booking_number }}</td></tr>
            <tr><td>Total Bayar</td><td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td></tr>
            <tr><td>Status</td><td style="color:#059669;">✓ Dikonfirmasi</td></tr>
        </table>

        <div class="note-box">
            <strong>Petunjuk Kehadiran:</strong><br>
            Hadir minimal 15 menit sebelum kelas dimulai. Tunjukkan email ini atau bukti booking PDF kepada panitia saat registrasi ulang.
        </div>

        <p style="font-size:13px; color:#6B7280;">Sampai jumpa di kelas! 🎨</p>
    </div>
    <div class="footer">FSRD UNS Store — Terima kasih telah mendaftar pelatihan kami</div>
</div>
</body>
</html>
