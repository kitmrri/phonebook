<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone_number' => $faker->phoneNumber,
        'company_id' => function () {
            return factory(App\Models\Company::class)->create()->id;
        },
        'department_id' => function () {
            return factory(App\Models\Department::class)->create()->id;
        },
    ];
});
