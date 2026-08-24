<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Instalasi') — FSRD UNS Store</title>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <style>
        body { margin:0; padding:0; }
        .install-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            background: #0A3D52;
            position: relative;
            overflow: hidden;
        }
        .install-bg::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(31,171,225,0.08);
            top: -200px; right: -200px;
        }
        .install-bg::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(233,168,40,0.06);
            bottom: -150px; left: -100px;
        }
        .install-card {
            background: white;
            border-radius: 20px;
            padding: 40px 40px 36px;
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 1;
            box-shadow: 0 24px 80px rgba(0,0,0,0.3);
            box-sizing: border-box;
        }
        .install-logo {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #E9A828, #FFDB07);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 900; color: white; font-size: 24px;
            margin: 0 auto 16px;
            box-shadow: 0 6px 20px rgba(233,168,40,0.4);
        }
        .install-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 20px; font-weight: 800;
            color: #0A3D52; text-align: center;
            margin-bottom: 4px;
        }
        .install-subtitle {
            font-size: 12px; color: #94A3B8;
            text-align: center; margin-bottom: 24px;
            font-family: 'Poppins', sans-serif;
        }
        .install-steps {
            display: flex; align-items: center; justify-content: center;
            gap: 6px; margin-bottom: 28px;
        }
        .install-step {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            font-family: 'Poppins', sans-serif;
            background: #F1F5F9; color: #94A3B8;
        }
        .install-step.active { background: #0E7DA7; color: white; }
        .install-step.done { background: #10B981; color: white; }
        .install-step-line { width: 20px; height: 2px; background: #F1F5F9; }
        .form-label {
            font-size: 12px; font-weight: 600;
            color: #475569; margin-bottom: 6px; display: block;
            font-family: 'Poppins', sans-serif;
        }
        .form-input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px; font-size: 13px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s; outline: none;
            color: #1A1A2E; background: #FAFAFA;
            box-sizing: border-box;
        }
        .form-input:focus {
            border-color: #0E7DA7;
            background: white;
            box-shadow: 0 0 0 3px rgba(14,125,167,0.1);
        }
        .form-hint { font-size: 11px; color: #94A3B8; margin-top: 4px; }
        .form-group { margin-bottom: 16px; }
        .form-row { display: flex; gap: 12px; }
        .form-row > .form-group { flex: 1; }
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #E9A828, #FFDB07);
            color: white; border: none; border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px; font-weight: 700;
            cursor: pointer; margin-top: 4px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(233,168,40,0.35);
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(233,168,40,0.45); }
        .btn-submit:active { transform: translateY(0); }
        .btn-back {
            display: block; text-align: center; margin-top: 14px;
            font-size: 12px; color: #94A3B8; text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }
        .btn-back:hover { color: #0E7DA7; }
        .alert-danger {
            background: #FEE2E2; color: #991B1B;
            border-radius: 8px; padding: 10px 14px;
            font-size: 12px; margin-bottom: 16px;
            border-left: 3px solid #EF4444;
            font-family: 'Poppins', sans-serif;
        }
        .alert-success {
            background: #D1FAE5; color: #065F46;
            border-radius: 8px; padding: 10px 14px;
            font-size: 12px; margin-bottom: 16px;
            border-left: 3px solid #10B981;
            font-family: 'Poppins', sans-serif;
        }
        .check-list { list-style: none; padding: 0; margin: 0 0 22px; }
        .check-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 0; border-bottom: 1px solid #F1F5F9;
            font-size: 12.5px; font-family: 'Poppins', sans-serif;
        }
        .check-item:last-child { border-bottom: none; }
        .check-icon {
            width: 20px; height: 20px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; flex-shrink: 0; color: white;
        }
        .check-icon.ok { background: #10B981; }
        .check-icon.fail { background: #EF4444; }
        .check-label { color: #1A1A2E; font-weight: 600; }
        .check-detail { color: #94A3B8; margin-left: auto; text-align: right; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 22px; font-family: 'Poppins', sans-serif; }
        .summary-table td { padding: 7px 0; font-size: 12.5px; border-bottom: 1px solid #F1F5F9; }
        .summary-table td:first-child { color: #94A3B8; width: 40%; }
        .summary-table td:last-child { color: #1A1A2E; font-weight: 600; text-align: right; }
        .success-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: #D1FAE5; color: #10B981;
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; margin: 0 auto 18px;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="install-bg">
    <div class="install-card">
        <div class="install-logo">F</div>
        <h1 class="install-title">@yield('heading', 'Instalasi FSRD UNS Store')</h1>
        <p class="install-subtitle">@yield('subheading', 'Wizard instalasi — tanpa perlu akses shell')</p>

        @if (isset($step))
            <div class="install-steps">
                @foreach (['Lisensi', 'Cek Server', 'Database', 'Akun Admin', 'Konfirmasi'] as $i => $label)
                    <div class="install-step {{ $i + 1 == $step ? 'active' : ($i + 1 < $step ? 'done' : '') }}">{{ $i + 1 < $step ? '✓' : $i + 1 }}</div>
                    @if (!$loop->last)
                        <div class="install-step-line"></div>
                    @endif
                @endforeach
            </div>
        @endif

        @if (session('info'))
            <div class="alert-success">{{ session('info') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-danger">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>
