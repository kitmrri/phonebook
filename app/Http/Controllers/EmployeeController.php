<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'number' => 'required|max:11',
            'department_id' => 'required|exists:departments,id'
        ]);

        $Employee = Employee::create([
            'name' => $validatedData['name'],
            'number' => $validatedData['number'],
            'department_id' => $validatedData['department_id']
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    }

    public function show($id)
    {
        $Employee = Employee::findOrFail($id);
        return view('employees.show', compact('Employee'));
    }

    public function edit($id)
    {
        $Employee = Employee::findOrFail($id);
        return view('employees.edit', compact('Employee'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'number' => 'required|max:11',
            'department_id' => 'required|exists:departments,id'
        ]);

        $Employee = Employee::findOrFail($id);
        $Employee->update([
            'name' => $validatedData['name'],
            'number' => $validatedData['number'],
            'department_id' => $validatedData['department_id']
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $Employee = Employee::findOrFail($id);
        $Employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
