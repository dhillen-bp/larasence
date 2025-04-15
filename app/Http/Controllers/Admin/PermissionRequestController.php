<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionRequestResource;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = PermissionRequest::with('user')->latest()->paginate(10);
        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => PermissionRequestResource::collection($permissions),
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
        $permission = PermissionRequest::with('user')->where('id', $id)->first();
        return Inertia::render('Admin/Permissions/Detail', [
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
    public function update(string $id)
    {
        $permission = PermissionRequest::findOrFail($id);
        $permission->update(['is_approved' => true]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission approved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve(string $id)
    {
        $permission = PermissionRequest::findOrFail($id);
        $permission->update(['is_approved' => true]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission approved successfully.');
    }
    public function reject(string $id)
    {
        $permission = PermissionRequest::findOrFail($id);
        $permission->update(['is_approved' => false]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission rejected successfully.');
    }
}
