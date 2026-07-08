<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use Illuminate\Http\Request;

class BuyerAccountController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'orders');

        $orders = Order::with(['items', 'bankAccount'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10, ['*'], 'orders_page');

        $bookings = Booking::with(['schedule.trainingClass'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10, ['*'], 'bookings_page');

        return view('buyer.account', compact('orders', 'bookings', 'tab'));
    }

    public function orderDetail($orderNumber)
    {
        $order = Order::with(['items', 'bankAccount'])
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('buyer.order-detail', compact('order'));
    }
}