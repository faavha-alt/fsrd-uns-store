@extends('layouts.admin')

@section('title', 'Rekening Bank')
@section('subtitle', 'Kelola rekening tujuan transfer untuk pembayaran')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Rekening</span>
        <a href="{{ route('admin.bank-accounts.create') }}" class="btn btn-primary btn-sm">+ Tambah Rekening</a>
    </div>
    <div class="panel-body" style="padding: 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin: 20px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Bank</th>
                    <th>No. Rekening</th>
                    <th>Atas Nama</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bankAccounts as $account)
                <tr>
                    <td><strong>{{ $account->bank_name }}</strong></td>
                    <td class="font-mono">{{ $account->account_number }}</td>
                    <td>{{ $account->account_holder }}</td>
                    <td>
                        @if($account->is_active)
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-gray">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form action="{{ route('admin.bank-accounts.destroy', $account) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus rekening ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-muted" style="text-align:center; padding: 30px;">Belum ada rekening.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
