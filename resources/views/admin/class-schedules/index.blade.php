@extends('layouts.admin')

@section('title', 'Jadwal: ' . $trainingClass->name)

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Jadwal untuk "{{ $trainingClass->name }}"</span>
        <a href="{{ route('admin.training-classes.schedules.create', $trainingClass) }}" class="btn btn-primary btn-sm">+ Tambah Jadwal</a>
    </div>
    <div class="panel-body" style="padding: 0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin: 20px;">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                    <th>Kuota</th>
                    <th>Terisi</th>
                    <th>Sisa Slot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr>
                    <td><strong>{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}</strong></td>
                    <td class="font-mono">{{ substr($schedule->start_time,0,5) }} - {{ substr($schedule->end_time,0,5) }}</td>
                    <td>{{ $schedule->location }}</td>
                    <td>{{ $schedule->quota }}</td>
                    <td>{{ $schedule->booked_count }}</td>
                    <td>
                        @if($schedule->isFull())
                            <span class="badge badge-red">Penuh</span>
                        @elseif($schedule->remainingSlots() <= 3)
                            <span class="badge badge-yellow">{{ $schedule->remainingSlots() }} slot</span>
                        @else
                            <span class="badge badge-green">{{ $schedule->remainingSlots() }} slot</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.training-classes.schedules.destroy', [$trainingClass, $schedule]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-muted" style="text-align:center; padding: 30px;">Belum ada jadwal untuk kelas ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('admin.training-classes.index') }}" class="btn btn-outline">← Kembali ke Daftar Kelas</a>
@endsection
