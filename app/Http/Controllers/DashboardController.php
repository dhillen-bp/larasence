<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::role('employee')->count();

        $totalAttendance = Attendance::whereDate('created_at', today())->count();

        $totalAbsent = $totalEmployees - $totalAttendance;

        $totalPresentOnTime = Attendance::whereDate('check_in', today())->where('status', 'on_time')->count();
        $totalPresentLate = Attendance::whereDate('check_in', today())->where('status', 'late')->count();

        $stillCheckIn = Attendance::whereDate('check_in', today())->where('status', 'pending')->whereNull('check_out')->count();

        $latestCheckIn = Attendance::whereDate('check_in', today())
            ->orderBy('check_in', 'desc')
            ->limit(10)
            ->get();
        // $latestCheckIn = Attendance::whereDate('check_in', today())
        //     ->orderBy('check_in', 'desc')
        //     ->limit(10)
        //     ->get();

        // return dd(AttendanceResource::collection($latestCheckIn));

        return Inertia::render('Index', [
            'totalEmployees' => $totalEmployees,
            'totalAttendance' => $totalAttendance,
            'totalAbsent' => $totalAbsent,
            'stillCheckIn' => $stillCheckIn,
            'totalPresentOnTime' => $totalPresentOnTime,
            'totalPresentLate' => $totalPresentLate,
            'latestCheckIn' => AttendanceResource::collection($latestCheckIn),
        ]);
    }
}
