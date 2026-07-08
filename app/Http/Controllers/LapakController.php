<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class LapakController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'creator'])
            ->where('status', 'approved')
            ->where('stock', '>', 0);

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) =>
                $q->where('slug', $request->category)
            );
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('creator', fn($q) =>
                      $q->where('name', 'like', "%{$search}%")
                  )
                  ->orWhereHas('category', fn($q) =>
                      $q->where('name', 'like', "%{$search}%")
                  );
            });
        }

        // Sort
        match($request->sort ?? 'latest') {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12);
        $categories = Category::where('type', 'produk')->orderBy('name')->get();

        return view('lapak.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'approved') abort(404);
        $product->load(['category', 'creator']);
        return view('lapak.show', compact('product'));
    }
}