@extends('layouts.admin')

@section('title', 'Management User')
@section('subtitle', 'Kelola Admin, Kurator, dan Buyer')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar User</span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Tambah User</a>
    </div>

    {{-- Filter Role --}}
    <div style="padding:12px 20px; border-bottom:1px solid var(--border); display:flex; gap:6px; flex-wrap:wrap;">
        <a href="{{ route('admin.users.index') }}"
           class="btn btn-sm {{ !request('role') ? 'btn-primary' : 'btn-outline' }}">Semua</a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="btn btn-sm {{ request('role') === 'admin' ? 'btn-primary' : 'btn-outline' }}">Admin</a>
        <a href="{{ route('admin.users.index', ['role' => 'kurator']) }}"
           class="btn btn-sm {{ request('role') === 'kurator' ? 'btn-primary' : 'btn-outline' }}">Kurator</a>
        <a href="{{ route('admin.users.index', ['role' => 'buyer']) }}"
           class="btn btn-sm {{ request('role') === 'buyer' ? 'btn-primary' : 'btn-outline' }}">Buyer</a>
    </div>

    <div class="panel-body" style="padding:0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin:16px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin:16px;">{{ session('error') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:32px; height:32px; border-radius:50%; background:var(--cerulean); color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; flex-shrink:0;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge badge-blue" style="font-size:9px;">Anda</span>
                            @endif
                        </div>
                    </td>
                    <td style="font-size:12px;">{{ $user->email }}</td>
                    <td style="font-size:12px; color:var(--muted);">{{ $user->phone ?? '-' }}</td>
                    <td>
                        @php
                            $roleBadge = [
                                'admin' => 'badge-red',
                                'kurator' => 'badge-blue',
                                'buyer' => 'badge-green',
                            ];
                        @endphp
                        <span class="badge {{ $roleBadge[$user->role->value] ?? 'badge-gray' }}">
                            {{ $user->role->label() }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-gray">Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:var(--muted);">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm">Edit</a>
                        {{-- Tombol reset password quick --}}
<button type="button" class="btn btn-outline btn-sm"
    onclick="document.getElementById('reset-modal-{{ $user->id }}').style.display='flex'"
    style="color:var(--gold); border-color:var(--gold);">
    🔑
</button>

{{-- Modal Reset Password --}}
<div id="reset-modal-{{ $user->id }}"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
           align-items:center; justify-content:center; z-index:999;">
    <div style="background:white; border-radius:12px; padding:24px; width:100%; max-width:380px;">
        <h3 style="font-size:15px; font-weight:700; color:var(--cerulean-dark); margin-bottom:6px;">
            Reset Password
        </h3>
        <p style="font-size:12px; color:var(--muted); margin-bottom:16px;">
            {{ $user->name }} ({{ $user->email }})
        </p>
        <form action="{{ route('admin.users.edit', $user) }}" method="GET"
            onsubmit="return false;">
        </form>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="role" value="{{ $user->role->value }}">
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control"
                    placeholder="Minimal 8 karakter" required minlength="8">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="Ulangi password baru" required>
            </div>
            <div style="display:flex; gap:8px; margin-top:8px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Simpan</button>
                <button type="button" class="btn btn-outline"
                    onclick="document.getElementById('reset-modal-{{ $user->id }}').style.display='none'">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline' : 'btn-primary' }}"
                                    onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Hapus user {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--muted);">Belum ada user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding:16px 20px;">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
