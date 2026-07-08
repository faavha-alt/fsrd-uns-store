<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — FSRD UNS Store</title>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}?v={{ filemtime(public_path('css/frontend.css')) }}">
    @stack('styles')
</head>
<body>
<div class="app-shell">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">F</div>
            <div class="sidebar-brand-text">
                <strong>FSRD UNS</strong>
                <small>{{ auth()->user()->role->label() }} Panel</small>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Konten</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🏺</span> Produk (Lapak)
            </a>
            <a href="{{ route('admin.training-classes.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.training-classes.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🎓</span> Pelatihan
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🏷️</span> Kategori
            </a>
            <a href="{{ route('admin.creators.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.creators.*') ? 'active' : '' }}">
                <span class="sidebar-icon">👤</span> Kreator
            </a>
        </div>

        @if(auth()->user()->role === \App\Enums\UserRole::Admin)

        <div class="sidebar-section">
            <div class="sidebar-label">Transaksi</div>
            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🛒</span> Order
                @php $pendingOrders = \App\Models\Order::where('status','pending_verification')->count(); @endphp
                @if($pendingOrders > 0)
                    <span class="sidebar-badge">{{ $pendingOrders }}</span>
                @endif
            </a>
            <a href="{{ route('admin.bookings.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <span class="sidebar-icon">📋</span> Booking
                @php $pendingBookings = \App\Models\Booking::where('status','pending_verification')->count(); @endphp
                @if($pendingBookings > 0)
                    <span class="sidebar-badge">{{ $pendingBookings }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <span class="sidebar-icon">📊</span> Laporan
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Pengguna</div>
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="sidebar-icon">👥</span> Management User
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-label">Pengaturan</div>
            <a href="{{ route('admin.bank-accounts.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.bank-accounts.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🏦</span> Rekening Bank
            </a>
            <a href="{{ route('admin.settings.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="sidebar-icon">⚙️</span> Pengaturan Site
            </a>
            <a href="{{ route('admin.email-settings.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.email-settings.*') ? 'active' : '' }}">
                <span class="sidebar-icon">📧</span> Pengaturan Email
            </a>
            <a href="{{ route('admin.activity-log.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
                <span class="sidebar-icon">🔐</span> Log Aktivitas
            </a>
        </div>

        @endif

    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="main-area">

        {{-- TOPBAR --}}
        <header class="topbar">

            {{-- Kiri: Toggle Sidebar --}}
            <div class="topbar-left">
                <button class="topbar-toggle"
                    onclick="document.getElementById('sidebar').classList.toggle('collapsed');
                             document.querySelector('.main-area').classList.toggle('sidebar-collapsed');">
                    ☰
                </button>
            </div>

            {{-- Kanan: Bell + User --}}
            <div style="display:flex; align-items:center; gap:8px;">

                {{-- Bell Notification --}}
                @php
                    $unreadCount   = \App\Helpers\NotificationHelper::getUnreadCount();
                    $notifications = \App\Helpers\NotificationHelper::getAll();
                @endphp
                <div style="position:relative;">
                    <button id="notifBtn" onclick="toggleNotif(event)"
                        style="position:relative; background:none; border:none; cursor:pointer;
                               width:38px; height:38px; border-radius:8px; font-size:18px;
                               display:flex; align-items:center; justify-content:center; transition:all 0.2s;"
                        onmouseover="this.style.background='var(--cream)'"
                        onmouseout="this.style.background='none'">
                        🔔
                        @if($unreadCount > 0)
                            <span style="position:absolute; top:4px; right:4px; width:18px; height:18px;
                                background:#EF4444; color:white; border-radius:50%; font-size:10px;
                                font-weight:700; display:flex; align-items:center; justify-content:center;
                                line-height:1;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div id="notifDropdown"
                        style="display:none; position:absolute; top:calc(100% + 8px); right:0;
                               width:320px; background:white; border:1px solid var(--border);
                               border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.12);
                               z-index:999; overflow:hidden;">

                        <div style="padding:12px 16px; border-bottom:1px solid var(--border);
                            display:flex; justify-content:space-between; align-items:center;">
                            <strong style="font-size:13px; color:var(--ink);">Notifikasi</strong>
                            @if($unreadCount > 0)
                                <a href="{{ route('admin.notifications.read-all') }}"
                                   style="font-size:11px; color:var(--cerulean); text-decoration:none; font-weight:600;">
                                    ✓ Tandai semua dibaca
                                </a>
                            @endif
                        </div>

                        <div style="max-height:320px; overflow-y:auto;">
                            @forelse($notifications as $notif)
                            <a href="{{ $notif['url'] ?: '#' }}"
                               style="display:flex; gap:10px; padding:12px 16px;
                                      border-bottom:1px solid var(--border); text-decoration:none;
                                      background:{{ $notif['read'] ? 'white' : '#F0F9FF' }};
                                      transition:background 0.15s;"
                               onmouseover="this.style.background='var(--cream)'"
                               onmouseout="this.style.background='{{ $notif['read'] ? 'white' : '#F0F9FF' }}'">
                                <div style="width:34px; height:34px; border-radius:8px; flex-shrink:0;
                                    display:flex; align-items:center; justify-content:center; font-size:16px;
                                    background:{{ $notif['type'] === 'order' ? 'var(--sky-pale)' : 'var(--gold-pale)' }};">
                                    {{ $notif['type'] === 'order' ? '🛒' : '🎓' }}
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <p style="font-size:12px; color:var(--ink); margin-bottom:3px; line-height:1.4;
                                        font-weight:{{ $notif['read'] ? '400' : '600' }};">
                                        {{ $notif['message'] }}
                                    </p>
                                    <span style="font-size:11px; color:var(--muted);">
                                        {{ \Carbon\Carbon::parse($notif['time'])->diffForHumans() }}
                                    </span>
                                </div>
                            </a>
                            @empty
                            <div style="padding:24px; text-align:center; color:var(--muted); font-size:13px;">
                                Belum ada notifikasi
                            </div>
                            @endforelse
                        </div>

                        <div style="padding:10px 16px; text-align:center; border-top:1px solid var(--border);">
                            <a href="{{ route('admin.orders.index') }}"
                               style="font-size:12px; color:var(--cerulean); font-weight:600;">
                                Lihat semua transaksi →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User Dropdown --}}
                <div class="topbar-user"
                    onclick="document.getElementById('userDropdown').classList.toggle('show')">
                    <div class="topbar-user-avatar">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="topbar-user-info">
                        <span class="topbar-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <span class="topbar-user-role">{{ auth()->user()->role->label() }}</span>
                    </div>
                    <span class="topbar-chevron">▾</span>
                    <div class="topbar-dropdown" id="userDropdown">
                        <div class="topbar-dropdown-header">
                            {{ auth()->user()->email }}
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">🚪 Keluar</button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <div class="page-content">
            <div class="page-header">
                <h1>@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')
                    <p>@yield('subtitle')</p>
                @endif
            </div>

            @yield('content')
        </div>

    </div>
</div>

<script>
    // Toggle notif dropdown
    function toggleNotif(e) {
        e.stopPropagation();
        const d = document.getElementById('notifDropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }

    // Close semua dropdown saat klik di luar
    document.addEventListener('click', function(e) {
        // User dropdown
        const userDropdown = document.getElementById('userDropdown');
        const user = document.querySelector('.topbar-user');
        if (userDropdown && user && !user.contains(e.target)) {
            userDropdown.classList.remove('show');
        }

        // Notif dropdown
        const notifDropdown = document.getElementById('notifDropdown');
        const notifBtn = document.getElementById('notifBtn');
        if (notifDropdown && notifBtn && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.style.display = 'none';
        }
    });
</script>

@stack('scripts')
</body>
</html>