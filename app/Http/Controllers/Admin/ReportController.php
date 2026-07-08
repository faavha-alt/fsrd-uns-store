<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use App\Exports\BookingsExport;
use App\Models\Order;
use App\Models\Booking;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $totalOrder          = Order::count();
        $totalBooking        = Booking::count();
        $totalRevenue        = Order::whereIn('status', ['confirmed','completed'])->sum('subtotal');
        $totalBookingRevenue = Booking::where('status', 'confirmed')->sum('total');

        return view('admin.reports.index', compact(
            'totalOrder','totalBooking','totalRevenue','totalBookingRevenue'
        ));
    }

    public function exportOrders(Request $request)
    {
        $filename = 'Laporan-Order-FSRD-' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new OrdersExport($request->start_date, $request->end_date), $filename);
    }

    public function exportBookings(Request $request)
    {
        $filename = 'Laporan-Booking-FSRD-' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new BookingsExport($request->start_date, $request->end_date), $filename);
    }
}
