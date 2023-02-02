<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index($companyId)
    {
        $departments = Department::where('company_id', $companyId)->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'company_id' => 'required'
        ]);

        $Department = Department::create([
            'name' => $validatedData['name'],
            'company_id' => $validatedData['company_id'],
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
    }

    public function show($id)
    {
        $Department = Department::findOrFail($id);
        return view('departments.show', compact('Department'));
    }

    public function edit($id)
    {
        $Department = Department::findOrFail($id);
        return view('departments.edit', compact('Department'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'company_id' => 'required'
        ]);

        $Department = Department::findOrFail($id);
        $Department->update([
            'name' => $validatedData['name'],
            'company_id' => $validatedData['company_id'],
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy($id)
    {
        $Department = Department::findOrFail($id);
        $Department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}
