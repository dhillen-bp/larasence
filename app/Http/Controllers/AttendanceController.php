<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();


        return Inertia::render('User/Attendance/Index', [
            'attendances' => AttendanceResource::collection($attendances)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Attendance/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        Attendance::create([
            'user_id' => Auth::id(),
            'check_in' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'on_time',
        ]);

        return redirect()->route('attendance.index')->with('success', 'Absen berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $existingAttendance = Attendance::where('user_id', Auth::user()->id)
            ->whereDate('check_in', today())
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'You have already checked in today.');
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'check_in' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Checked in successfully');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No active check-in found.');
        }

        $attendance->update([
            'check_out' => now(),
        ]);

        return back()->with('success', 'Checked out successfully');
    }
}
