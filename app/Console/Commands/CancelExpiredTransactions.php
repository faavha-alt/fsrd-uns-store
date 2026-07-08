<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Booking;
use Illuminate\Console\Command;

class CancelExpiredTransactions extends Command
{
    protected $signature   = 'transactions:cancel-expired';
    protected $description = 'Auto-cancel order & booking yang belum bayar dalam 24 jam';

    public function handle()
    {
        // Cancel order expired
        $orders = Order::where('status', 'pending_payment')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($orders as $order) {
            $order->update(['status' => 'cancelled']);
            // Kembalikan stok
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
            $this->info("Order {$order->order_number} dibatalkan.");
        }

        // Cancel booking expired
        $bookings = Booking::where('status', 'pending_payment')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($bookings as $booking) {
            $booking->update(['status' => 'cancelled']);
            // Kembalikan slot
            if ($booking->schedule) {
                $booking->schedule->decrement('booked_count');
            }
            $this->info("Booking {$booking->booking_number} dibatalkan.");
        }

        $this->info("Selesai: {$orders->count()} order, {$bookings->count()} booking dibatalkan.");
    }
}
