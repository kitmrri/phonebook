<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Implementations\QueueAdder;
use App\Utilities\RedisQueue;

class CompanyController extends Controller
{
    public function sendSmsQueue(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        $employees = $company->employees()->get();

        $queueAdder = new QueueAdder(new RedisQueue());
        $queueAdder->addToQueue($employees, $request->message);
        return response()->json(['message' => 'SMS added to queue for processing']);
    }

}
