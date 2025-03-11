<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Traits\CalculatesDistance;
use Ballen\Distical\Entities\LatLong;
use Ballen\Distical\Calculator as DistanceCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    use CalculatesDistance;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return dd(AttendanceResource::collection($attendances));

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
        // Validasi input
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude  = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        // Cek apakah user sudah check-in hari ini
        $existingAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('check_in', today())
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'You have already checked in today.');
        }

        $distanceInMeters = $this->calculateDistance($latitude, $longitude);

        // Validasi jarak: maksimal 15 meter
        if ($distanceInMeters > 15) {
            return back()->with('error', 'You are too far from the allowed check-in location.');
        }

        // Simpan data check-in
        Attendance::create([
            'user_id'   => Auth::id(),
            'check_in'  => now(),
            'latitude'  => $latitude,
            'longitude' => $longitude,
        ]);

        return back()->with('success', 'Checked in successfully');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $latitude  = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        // return dd([$latitude, $longitude]);

        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No active check-in found.');
        }

        $distanceInMeters = $this->calculateDistance($latitude, $longitude);

        // Validasi jarak: maksimal 15 meter
        if ($distanceInMeters > 15) {
            return back()->with('error', 'You are too far from the allowed check-in location.');
        }

        $checkInTime = $attendance->check_in;
        $checkOutTime = now();

        // Konversi check_in menjadi objek Carbon
        $checkInTime = Carbon::parse($attendance->check_in);
        $checkOutTime = now();

        // Waktu minimal checkout (8 jam setelah check-in)
        $requiredCheckOutTime = $checkInTime->copy()->addHours(8);

        // Waktu batas toleransi (15 menit setelah required checkout)
        $toleranceTime = $requiredCheckOutTime->copy()->addMinutes(15);

        if ($checkOutTime->lessThan($requiredCheckOutTime)) {
            return back()->with('error', 'You cannot check out yet. Minimum 8 hours required.');
        }

        // Tentukan status berdasarkan waktu check-out
        $status = $checkOutTime->lessThanOrEqualTo($toleranceTime) ? 'On Time' : 'Late';

        $attendance->update([
            'check_out' => now(),
            'status' => $status
        ]);

        return back()->with('success', 'Checked out successfully');
    }
}
