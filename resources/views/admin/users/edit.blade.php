@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Edit User</span>
        <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-gray' }}">
            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
        </span>
    </div>
    <div class="panel-body">
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required
                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="admin" {{ old('role', $user->role->value) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kurator" {{ old('role', $user->role->value) === 'kurator' ? 'selected' : '' }}>Kurator</option>
                    <option value="buyer" {{ old('role', $user->role->value) === 'buyer' ? 'selected' : '' }}>Buyer</option>
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role->value }}">
                    <small style="color:var(--muted); font-size:11px;">Role tidak bisa diubah untuk akun sendiri.</small>
                @endif
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Password Baru (opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ubah">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div style="background:var(--cream); border-radius:8px; padding:14px; margin-bottom:16px; font-size:13px; color:var(--muted);">
                <strong style="color:var(--ink); display:block; margin-bottom:4px;">Info Akun</strong>
                Bergabung: {{ $user->created_at->format('d M Y, H:i') }} WIB
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
