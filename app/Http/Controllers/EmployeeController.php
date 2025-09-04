<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 20;
        $search = $request->input('search');

        $query = Employee::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $employees = $query->paginate($perPage);

        if ($request->ajax()) {
            return view('employees.rows', compact('employees'))->render();
        }

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // If documents is string JSON → decode to array
        if (isset($data['documents']) && is_string($data['documents'])) {
            $decoded = json_decode($data['documents'], true);
            $data['documents'] = $decoded ?? [];
        }

        $validator = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone_no' => 'nullable|string|max:20',
            'salary' => 'nullable|numeric|min:0',
            'doj' => 'nullable|date',
            'dob' => 'nullable|date',
            'ifsc_code' => 'nullable|string|max:20',
            'account_holder_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:30',
            'documents' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['status'] = 'active'; // 👈 default
        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->all();

        // If documents is JSON string from textarea → decode to array
        if (isset($data['documents']) && is_string($data['documents'])) {

            $decoded = json_decode($data['documents'], true);
            $data['documents'] = $decoded ?? [];
        }

        $validator = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone_no' => 'nullable|string|max:20',
            'salary' => 'nullable|numeric|min:0',
            'doj' => 'nullable|date',
            'dob' => 'nullable|date',
            'ifsc_code' => 'nullable|string|max:20',
            'account_holder_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:30',
            'documents' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee->update($data);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
