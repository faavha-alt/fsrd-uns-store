<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Product;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Creator::withCount(['products' => function($q) {
            $q->where('status', 'approved');
        }]);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('department', 'like', '%'.$request->search.'%');
            });
        }

        $creators = $query->orderBy('name')->paginate(12);

        return view('creator.index', compact('creators'));
    }

    public function show(Creator $creator)
    {
        $products = Product::with(['category'])
            ->where('creator_id', $creator->id)
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        $totalProduk = Product::where('creator_id', $creator->id)
            ->where('status', 'approved')
            ->count();

        return view('creator.show', compact('creator', 'products', 'totalProduk'));
    }
}