<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service1 = Service::create(["name" => "Cash Deposit", "code_name" => "CD"]);
        $service2 = Service::create(["name" => "Cash Withdrawal", "code_name" => "CW"]);
        $service3 = Service::create(["name" => "Cash Encashment", "code_name" => "CE"]);
        $service4 = Service::create(["name" => "Bills Payment", "code_name" => "BP"]);
        $service5 = Service::create(["name" => "Loan Transaction", "code_name" => "LT"]);
        $service6 = Service::create(["name" => "New Account", "code_name" => "NA"]);
    }
}
