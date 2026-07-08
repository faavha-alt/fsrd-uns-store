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
        .header p { color: rgba(255,255,255,0.8); font-size: 13px; }
        .checkmark { font-size: 48px; margin-bottom: 12px; display: block; }
        .body { padding: 28px 32px; }
        .success-box { background: #D1FAE5; border: 1px solid #6EE7B7; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; font-size: 13px; color: #065F46; text-align: center; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 10px 0; border-bottom: 1px solid #F1F5F9; font-size: 13px; }
        .info-table td:first-child { color: #6B7280; width: 140px; }
        .info-table td:last-child { font-weight: 600; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #F8FAFC; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #94A3B8; border-bottom: 1px solid #E5E7EB; }
        .items-table td { padding: 12px; font-size: 13px; border-bottom: 1px solid #F1F5F9; }
        .footer { background: #F8FAFC; padding: 20px 32px; text-align: center; font-size: 12px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <span class="checkmark">✅</span>
        <h1>Pembayaran Dikonfirmasi!</h1>
        <p>Pesanan Anda telah berhasil diverifikasi</p>
    </div>
    <div class="body">
        <p style="font-size:14px; margin-bottom:16px;">Halo, <strong>{{ $order->buyer_name }}</strong>!</p>
        <div class="success-box">
            🎉 Selamat! Pembayaran Anda untuk order <strong>{{ $order->order_number }}</strong> telah dikonfirmasi.
        </div>

        <table class="info-table">
            <tr><td>No. Order</td><td style="font-family:monospace;">{{ $order->order_number }}</td></tr>
            <tr><td>Tanggal</td><td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td></tr>
            <tr><td>Status</td><td style="color:#059669; font-weight:700;">✓ Dikonfirmasi</td></tr>
        </table>

        <table class="items-table">
            <thead>
                <tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align:right; color:#6B7280;">Total</td>
                    <td style="font-weight:700; color:#0A5F80;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size:13px; color:#6B7280; line-height:1.7;">
            Tim FSRD UNS akan segera memproses pesanan Anda. Jika ada pertanyaan, silakan hubungi kami melalui email atau WhatsApp.
        </p>
    </div>
    <div class="footer">
        FSRD UNS Store — Terima kasih telah berbelanja!
    </div>
</div>
</body>
</html>
