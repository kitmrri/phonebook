<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;

class UserCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create();
        $user->company_id = $company->id;
        $user->save();
    }
}
