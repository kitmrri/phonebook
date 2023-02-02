<?php

namespace App\Interfaces;
use App\Models\Company;

interface Recipient
{
    public function create(Request $request);
}

class AllRecipient implements Recipient
{
    public function create(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        return $company->employees()->get();
    }
}

class EmployeeRecipient implements Recipient
{
    public function create(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        return $company->employees()->where('id', $request->employee_id)->get();
    }
}

class SelectedRecipient implements Recipient
{
    public function create(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        return $company->employees()->whereIn('id', $request->employee_ids)->get();
    }
}

class DepartmentRecipient implements Recipient
{
    public function create(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        return $company->employees()->whereHas('department', function ($query) use ($request) {
            $query->where('id', $request->department_id);
        })->get();
    }
}

class RecipientFactory
{
    public static function create(Request $request)
    {
        switch ($request->recipient_type) {
            case 'all':
                return new AllRecipient();
                break;
            case 'employee':
                return new EmployeeRecipient();
                break;
            case 'selected':
                return new SelectedRecipient();
                break;
            case 'department':
                return new DepartmentRecipient();
                break;
            default:
                throw new \Exception('Invalid recipient type');
        }
    }
}