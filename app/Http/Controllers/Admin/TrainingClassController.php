<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingClass;
use App\Models\Category;
use App\Models\Creator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class TrainingClassController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingClass::with(['category', 'instructor', 'schedules'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainingClasses = $query->paginate(15);

        return view('admin.training-classes.index', compact('trainingClasses'));
    }

    public function create()
    {
        $categories = Category::where('type', 'pelatihan')->orderBy('name')->get();
        $creators = Creator::orderBy('name')->get();

        return view('admin.training-classes.create', compact('categories', 'creators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'creator_id' => 'required|exists:creators,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['curator_id'] = auth()->id();
        $validated['status'] = 'pending';

        if ($request->hasFile('image')) {
    ImageHelper::delete($trainingClass->image);
    $validated['image'] = ImageHelper::upload(
        $request->file('image'),
        'training-classes',
        800, 500
    );
}

        TrainingClass::create($validated);

        return redirect()->route('admin.training-classes.index')->with('success', 'Kelas pelatihan berhasil diajukan, menunggu persetujuan Admin.');
    }

    public function edit(TrainingClass $trainingClass)
    {
        $categories = Category::where('type', 'pelatihan')->orderBy('name')->get();
        $creators = Creator::orderBy('name')->get();

        return view('admin.training-classes.edit', compact('trainingClass', 'categories', 'creators'));
    }

    public function update(Request $request, TrainingClass $trainingClass)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'creator_id' => 'required|exists:creators,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('training-classes', 'public');
        }

        $trainingClass->update($validated);

        return redirect()->route('admin.training-classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(TrainingClass $trainingClass)
    {
        $trainingClass->delete();

        return redirect()->route('admin.training-classes.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function approve(TrainingClass $trainingClass)
    {
        $trainingClass->update(['status' => 'approved']);

        return back()->with('success', 'Kelas berhasil disetujui.');
    }

    public function reject(Request $request, TrainingClass $trainingClass)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $trainingClass->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Kelas ditolak.');
    }
}