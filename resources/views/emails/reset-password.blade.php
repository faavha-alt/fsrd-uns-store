<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #F0F4F8; color: #1A1A2E; font-size: 14px; }
        .wrap { max-width: 580px; margin: 32px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .header { background: #0E7DA7; padding: 28px 32px; text-align: center; }
        .header h1 { color: white; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .header p  { color: rgba(255,255,255,0.75); font-size: 13px; }
        .body { padding: 32px; }
        .btn { display: block; background: #E9A828; color: white; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 15px; text-align: center; margin: 24px 0; }
        .note { background: #F8FAFC; border: 1px solid #E5E7EB; border-radius: 8px; padding: 14px; font-size: 12px; color: #6B7280; line-height: 1.7; }
        .footer { background: #F8FAFC; padding: 20px 32px; text-align: center; font-size: 12px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <span style="font-size:40px; display:block; margin-bottom:12px;">🔐</span>
        <h1>Reset Password</h1>
        <p>Permintaan reset password akun FSRD UNS Store</p>
    </div>
    <div class="body">
        <p style="margin-bottom:16px;">Halo, <strong>{{ $user->name }}</strong>!</p>
        <p style="font-size:13px; color:#6B7280; line-height:1.7; margin-bottom:8px;">
            Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk membuat password baru.
        </p>

        <a href="{{ url('/reset-password/'.$token.'?email='.urlencode($email)) }}" class="btn">
            🔑 Reset Password Saya
        </a>

        <div class="note">
            <strong>⚠️ Perhatian:</strong><br>
            • Link ini hanya berlaku selama <strong>60 menit</strong><br>
            • Jika Anda tidak meminta reset password, abaikan email ini<br>
            • Password lama Anda tetap aman selama link ini tidak diklik
        </div>
    </div>
    <div class="footer">FSRD UNS Store — Jangan balas email ini</div>
</div>
</body>
</html>
