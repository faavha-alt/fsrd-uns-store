<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #F0F4F8; color: #1A1A2E; font-size: 14px; }
        .wrap { max-width: 580px; margin: 32px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .header { background: #0E7DA7; padding: 28px 32px; }
        .header h1 { color: white; font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .header p { color: rgba(255,255,255,0.7); font-size: 13px; }
        .body { padding: 28px 32px; }
        .alert-box { background: #FEF9C3; border: 1px solid #FCD34D; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; font-size: 13px; color: #92400E; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 10px 0; border-bottom: 1px solid #F1F5F9; font-size: 13px; }
        .info-table td:first-child { color: #6B7280; width: 140px; }
        .info-table td:last-child { font-weight: 600; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #F8FAFC; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #94A3B8; border-bottom: 1px solid #E5E7EB; }
        .items-table td { padding: 12px; font-size: 13px; border-bottom: 1px solid #F1F5F9; }
        .total-box { background: #E6F7FD; border: 1px solid #1FABE1; border-radius: 8px; padding: 16px; text-align: center; margin-bottom: 20px; }
        .total-box .amount { font-size: 24px; font-weight: 700; color: #0A5F80; font-family: monospace; }
        .btn { display: inline-block; background: #0E7DA7; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px; }
        .footer { background: #F8FAFC; padding: 20px 32px; text-align: center; font-size: 12px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🛒 Order Baru Masuk!</h1>
        <p>Ada order baru yang perlu diverifikasi pembayarannya</p>
    </div>
    <div class="body">
        <div class="alert-box">
            ⚡ Segera verifikasi bukti transfer dari pembeli untuk memproses pesanan ini.
        </div>

        <table class="info-table">
            <tr><td>No. Order</td><td style="font-family:monospace;">{{ $order->order_number }}</td></tr>
            <tr><td>Nama Pembeli</td><td>{{ $order->buyer_name }}</td></tr>
            <tr><td>Email</td><td>{{ $order->buyer_email }}</td></tr>
            <tr><td>Telepon</td><td>{{ $order->buyer_phone }}</td></tr>
            <tr><td>Tanggal</td><td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td></tr>
            <tr><td>Rekening</td><td>{{ $order->bankAccount->bank_name ?? '-' }} — {{ $order->bankAccount->account_number ?? '-' }}</td></tr>
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
            </tbody>
        </table>

        <div class="total-box">
            <div style="font-size:12px; color:#6B7280; margin-bottom:4px;">Total Transfer (termasuk kode unik)</div>
            <div class="amount">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
        </div>

        <div style="text-align:center;">
            <a href="{{ url('/admin/orders/'.$order->id) }}" class="btn">Verifikasi Sekarang →</a>
        </div>
    </div>
    <div class="footer">
        FSRD UNS Store — Notifikasi Otomatis · Jangan balas email ini
    </div>
</div>
</body>
</html>
