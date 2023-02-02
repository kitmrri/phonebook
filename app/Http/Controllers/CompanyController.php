<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Implementations\QueueAdder;
use App\Utilities\RedisQueue;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $company = Company::create([
            'name' => $validatedData['name'],
        ]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $company = Company::findOrFail($id);
        $company->update([
            'name' => $validatedData['name'],
        ]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }

    // SMS QUEUE AND SENDING
    public function sendSmsQueue(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        $employees = $company->employees();

        if ($request->recipient_type === 'all') {
            $employees = $employees->get();
        } else if ($request->recipient_type === 'employee') {
            $employees = $employees->where('id', $request->employee_id)->get();
        } else if ($request->recipient_type === 'selected') {
            $employees = $employees->whereIn('id', $request->employee_ids)->get();
        } else if ($request->recipient_type === 'department') {
            $employees = $employees->whereHas('department', function ($query) use ($request) {
                $query->where('id', $request->department_id);
            })->get();
        }

        $queueAdder = new QueueAdder(new RedisQueue());
        $queueAdder->addToQueue($employees, $request->message);
        return response()->json(['message' => 'SMS added to queue for processing']);
    }

    
}
