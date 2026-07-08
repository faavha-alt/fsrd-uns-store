<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        $query = Booking::with(['schedule.trainingClass', 'bankAccount'])->orderBy('created_at', 'desc');

        if ($this->startDate) $query->whereDate('created_at', '>=', $this->startDate);
        if ($this->endDate)   $query->whereDate('created_at', '<=', $this->endDate);

        return $query->get()->map(function ($booking) {
            return [
                'No. Booking'  => $booking->booking_number,
                'Tanggal'      => $booking->created_at->format('d/m/Y H:i'),
                'Nama Peserta' => $booking->participant_name,
                'Email'        => $booking->participant_email,
                'Telepon'      => $booking->participant_phone,
                'Instansi'     => $booking->institution ?? '-',
                'Kelas'        => $booking->schedule->trainingClass->name ?? '-',
                'Tgl. Kelas'   => $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->date)->format('d/m/Y') : '-',
                'Lokasi'       => $booking->schedule->location ?? '-',
                'Total Bayar'  => $booking->total,
                'Status'       => [
                    'pending_payment'      => 'Belum Bayar',
                    'pending_verification' => 'Menunggu Verifikasi',
                    'confirmed'            => 'Dikonfirmasi',
                    'rejected'             => 'Ditolak',
                    'cancelled'            => 'Dibatalkan',
                ][$booking->status] ?? $booking->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['No. Booking','Tanggal','Nama Peserta','Email','Telepon','Instansi','Kelas','Tgl. Kelas','Lokasi','Total Bayar','Status'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF0E7DA7']],
            ],
        ];
    }
}
