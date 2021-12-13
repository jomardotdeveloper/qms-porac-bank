<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loan1 = Loan::create([
            "name" => "Agricultural Loan"
        ]);
        $loan2 = Loan::create([
            "name" => "Commercial Loan"
        ]);
        $loan3 = Loan::create([
            "name" => "Housing Loan"
        ]);
        $loan4 = Loan::create([
            "name" => "Easy Cash"
        ]);
        $loan5 = Loan::create([
            "name" => "SSS Pensioners"
        ]);
    }
}
