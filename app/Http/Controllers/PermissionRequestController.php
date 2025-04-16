<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionRequestResource;
use App\Models\Attendance;
use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PermissionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $permissions = PermissionRequest::with('user')->where('user_id', $user_id)->latest()->paginate(10);
        return Inertia::render('User/Attendance/Permission/Index', [
            'permissions' => PermissionRequestResource::collection($permissions),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Attendance/Permission/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:leave,permission,sick',
            'note' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|max:2048',
        ]);

        // return dd($validated);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('permissions', 'public');
        }

        $validated['user_id'] = auth()->id();

        DB::beginTransaction();

        try {
            $permissionRequest = PermissionRequest::create($validated);

            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                Attendance::create([
                    'user_id' => auth()->id(),
                    'check_in' => $date->copy()->setTime(9, 0, 0),
                    'check_out' => $date->copy()->setTime(17, 0, 0),
                    'status' => 'permission',
                    'permission_request_id' => $permissionRequest->id,
                    'created_at' => $date->copy()->startOfDay(),
                    'updated_at' => $date->copy()->startOfDay(),
                ]);
            }

            DB::commit();

            return redirect()->route('permission.index')->with('success', 'Permission request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Optional: log error atau tampilkan pesan
            // Log::error('Permission request failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = PermissionRequest::with('user')->where('id', $id)->first();
        return Inertia::render('User/Attendance/Permission/Detail', [
            'permission' => new PermissionRequestResource($permission),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
