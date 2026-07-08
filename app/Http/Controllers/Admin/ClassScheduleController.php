<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingClass;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index(TrainingClass $trainingClass)
    {
        $schedules = $trainingClass->schedules()->orderBy('date')->get();
        return view('admin.class-schedules.index', compact('trainingClass', 'schedules'));
    }

    public function create(TrainingClass $trainingClass)
    {
        return view('admin.class-schedules.create', compact('trainingClass'));
    }

    public function store(Request $request, TrainingClass $trainingClass)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'quota' => 'required|integer|min:1',
        ]);

        $trainingClass->schedules()->create($validated);

        return redirect()->route('admin.training-classes.schedules.index', $trainingClass)->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(TrainingClass $trainingClass, ClassSchedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}