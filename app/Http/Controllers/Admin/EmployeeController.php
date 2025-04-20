<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'employee');
        });

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // $employees = $query->paginate(5)->withQueryString();
        $employees = $query->get();

        return Inertia::render('Admin/Employee/Index', [
            'employees' => UserResource::collection($employees),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Employee/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('employee');

        return redirect()->route('admin.employees.index')->with('success', 'Employee added successfully!');
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
        $employee = User::findOrFail($id);
        return Inertia::render('Admin/Employee/Edit', [
            'employee' => $employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'nullable|min:3',
        ]);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];

        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully!');
    }

    public function export()
    {
        // Log::info('Metode export dipanggil.');
        return Excel::download(new EmployeesExport, 'employees.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
