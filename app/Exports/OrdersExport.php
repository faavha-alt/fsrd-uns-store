<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $query = Order::with(['items', 'bankAccount'])->orderBy('created_at', 'desc');

        if ($this->startDate) $query->whereDate('created_at', '>=', $this->startDate);
        if ($this->endDate)   $query->whereDate('created_at', '<=', $this->endDate);

        return $query->get()->map(function ($order) {
            return [
                'No. Order'      => $order->order_number,
                'Tanggal'        => $order->created_at->format('d/m/Y H:i'),
                'Nama Pembeli'   => $order->buyer_name,
                'Email'          => $order->buyer_email,
                'Telepon'        => $order->buyer_phone,
                'Produk'         => $order->items->pluck('product_name')->implode(', '),
                'Subtotal'       => $order->subtotal,
                'Kode Unik'      => $order->unique_code,
                'Total Transfer' => $order->total,
                'Rekening'       => $order->bankAccount->bank_name ?? '-',
                'Status'         => [
                    'pending_payment'      => 'Belum Bayar',
                    'pending_verification' => 'Menunggu Verifikasi',
                    'confirmed'            => 'Dikonfirmasi',
                    'rejected'             => 'Ditolak',
                    'completed'            => 'Selesai',
                    'cancelled'            => 'Dibatalkan',
                ][$order->status] ?? $order->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['No. Order','Tanggal','Nama Pembeli','Email','Telepon','Produk','Subtotal','Kode Unik','Total Transfer','Rekening','Status'];
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
