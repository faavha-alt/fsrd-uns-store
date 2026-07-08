<?php

namespace App\Http\Controllers;

use App\Models\TrainingClass;
use App\Models\Category;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingClass::with(['category', 'instructor', 'schedules'])
            ->where('status', 'approved');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        $trainingClasses = $query->latest()->paginate(12);
        $categories = Category::where('type', 'pelatihan')->orderBy('name')->get();

        return view('pelatihan.index', compact('trainingClasses', 'categories'));
    }

    public function show(TrainingClass $trainingClass)
    {
        if ($trainingClass->status !== 'approved') abort(404);
        $trainingClass->load(['instructor', 'category', 'schedules']);
        return view('pelatihan.show', compact('trainingClass'));
    }
}