<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #F0F4F8; color: #1A1A2E; font-size: 14px; }
        .wrap { max-width: 580px; margin: 32px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .header { background: #DC2626; padding: 28px 32px; text-align: center; }
        .header h1 { color: white; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p { color: rgba(255,255,255,0.8); font-size: 13px; }
        .body { padding: 28px 32px; }
        .reason-box { background: #FEE2E2; border: 1px solid #FCA5A5; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; font-size: 13px; color: #991B1B; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 10px 0; border-bottom: 1px solid #F1F5F9; font-size: 13px; }
        .info-table td:first-child { color: #6B7280; width: 140px; }
        .info-table td:last-child { font-weight: 600; }
        .footer { background: #F8FAFC; padding: 20px 32px; text-align: center; font-size: 12px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <span style="font-size:48px; display:block; margin-bottom:12px;">❌</span>
        <h1>Pembayaran Ditolak</h1>
        <p>Ada kendala dengan verifikasi pembayaran Anda</p>
    </div>
    <div class="body">
        <p style="font-size:14px; margin-bottom:16px;">Halo, <strong>{{ $order->buyer_name }}</strong>!</p>
        <p style="font-size:13px; color:#6B7280; margin-bottom:16px; line-height:1.7;">
            Mohon maaf, pembayaran untuk order <strong>{{ $order->order_number }}</strong> tidak dapat dikonfirmasi dengan alasan berikut:
        </p>
        <div class="reason-box">
            <strong>Alasan:</strong> {{ $order->rejection_reason }}
        </div>

        <table class="info-table">
            <tr><td>No. Order</td><td style="font-family:monospace;">{{ $order->order_number }}</td></tr>
            <tr><td>Total</td><td>Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
        </table>

        <p style="font-size:13px; color:#6B7280; line-height:1.7;">
            Silakan hubungi kami untuk informasi lebih lanjut atau lakukan pemesanan ulang. Mohon maaf atas ketidaknyamanan ini.
        </p>
    </div>
    <div class="footer">FSRD UNS Store — Hubungi kami jika ada pertanyaan</div>
</div>
</body>
</html>
