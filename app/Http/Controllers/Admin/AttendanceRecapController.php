<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceRecapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('check_in', $request->date);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(5)->withQueryString();

        return Inertia::render('Admin/Attendance/Index', [
            'attendances' => AttendanceResource::collection($attendances),
            'filters' => $request->only(['search', 'date']),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::with('user')
            ->where('id', $id)
            ->latest()
            ->first();
        return Inertia::render('Admin/Attendance/Edit', [
            'attendance' => new AttendanceResource($attendance),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'status' => 'required|in:on_time,absent,late,pending',
        ]);

        $attendance->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.attendances.index')->with('success', 'Attendance status changed successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance deleted successfully!');
    }
}
