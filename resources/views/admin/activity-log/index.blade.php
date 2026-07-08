@extends('layouts.admin')

@section('title', 'Log Aktivitas Admin')
@section('subtitle', 'Riwayat login & logout admin')

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">🔐 Log Aktivitas Admin</span>
        <span style="font-size:12px; color:var(--muted);">100 aktivitas terakhir</span>
    </div>
    <div class="panel-body" style="padding:0;">
        @if(count($logs) > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td style="font-size:12px; color:var(--muted); white-space:nowrap;">
                        {{ $log['time'] }}
                    </td>
                    <td>
                        @if(str_contains($log['action'], 'login'))
                            <span class="badge badge-green">🔓 Login</span>
                        @else
                            <span class="badge badge-gray">🔒 Logout</span>
                        @endif
                    </td>
                    <td><strong>{{ $log['name'] }}</strong></td>
                    <td style="font-size:12px;">{{ $log['email'] }}</td>
                    <td>
                        <span class="font-mono" style="font-size:12px; background:var(--cream); padding:2px 8px; border-radius:4px;">
                            {{ $log['ip'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center; padding:40px; color:var(--muted);">
            <div style="font-size:36px; margin-bottom:12px;">📋</div>
            <p>Belum ada aktivitas tercatat.</p>
        </div>
        @endif
    </div>
</div>
@endsection
