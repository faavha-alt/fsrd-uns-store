<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Creator;
use App\Models\Order;
use App\Models\TrainingClass;

class HomeController extends Controller
{
    public function index()
    {
        $produkUnggulan = Product::with(['category', 'creator'])
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $kelasUnggulan = TrainingClass::with(['category', 'instructor', 'schedules'])
            ->where('status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        $kategoris = Category::where('type', 'produk')->orderBy('name')->get();

        $totalProduk = Product::where('status', 'approved')->count();
        $totalKreator = Creator::count();
        $totalOrder = Order::whereIn('status', ['confirmed', 'completed'])->count();

        return view('home', compact(
            'produkUnggulan',
            'kelasUnggulan',
            'kategoris',
            'totalProduk',
            'totalKreator',
            'totalOrder'
        ));
    }
}