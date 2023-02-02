<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'number' => 'required|max:11'
        ]);

        $Employee = Employee::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'number' => $validatedData['number']
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
        ]);

        $Employee = Employee::findOrFail($id);
        $Employee->update([
            'name' => $validatedData['name'],
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
