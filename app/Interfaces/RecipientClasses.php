<?php

namespace App\Interfaces;

use App\Interfaces\Recipient;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AllRecipient implements Recipient
{
	public function create(Request $request)
	{
		try {
			$company = Company::findOrFail($request->company_id);
		} catch (ModelNotFoundException $e) {
			return response()->json(['error' => 'Company not found'], 404);
		}

		return $company->employees()->get();
	}
}

class EmployeeRecipient implements Recipient
{
	public function create(Request $request)
	{
		try {
			$company = Company::findOrFail($request->company_id);
		} catch (ModelNotFoundException $e) {
			return response()->json(['error' => 'Company not found'], 404);
		}

		return $company->employees()->where('id', $request->employee_id)->get();
	}
}

class SelectedRecipient implements Recipient
{
	public function create(Request $request)
	{
		try {
			$company = Company::findOrFail($request->company_id);
		} catch (ModelNotFoundException $e) {
			return response()->json(['error' => 'Company not found'], 404);
		}

		return $company->employees()->whereIn('id', $request->employee_ids)->get();
	}
}

class DepartmentRecipient implements Recipient
{
	public function create(Request $request)
	{
		try {
			$company = Company::findOrFail($request->company_id);
		} catch (ModelNotFoundException $e) {
			return response()->json(['error' => 'Company not found'], 404);
		}

		return $company->employees()->whereHas('department', function ($query) use ($request) {
			$query->where('id', $request->department_id);
		})->get();
	}
}