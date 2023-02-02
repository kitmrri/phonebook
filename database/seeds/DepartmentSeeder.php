<?php

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            // Create a department for the company
            $department = factory(Department::class)->create([
                'company_id' => $company->id
            ]);

            // Create employees for the department
            $employees = factory(Employee::class, 5)->create([
                'company_id' => $company->id,
                'department_id' => $department->id
            ]);
        }
    }
}
