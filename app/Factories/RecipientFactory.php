<?php

namespace App\Factories;

use App\Interfaces\Recipient;
use App\Factories\AllRecipient;
use App\Factories\EmployeeRecipient;
use App\Factories\SelectedRecipient;
use App\Factories\DepartmentRecipient;
use Illuminate\Http\Request;

class RecipientFactory
{
    public static function create(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            throw $e;
        }
    }
}