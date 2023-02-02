<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{

    public function index($company_id)
    {
        $employees = Employee::where('company_id', $company_id)->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'number' => 'required',
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
            'number' => 'required',
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
