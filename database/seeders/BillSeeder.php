<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bill;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bill1 = Bill::create([
            "name" => "Philippine Airlines"
        ]);
        $bill2 = Bill::create([
            "name" => "SMART"
        ]);
        $bill3 = Bill::create([
            "name" => "Converge"
        ]);
        $bill4 = Bill::create([
            "name" => "Globe"
        ]);
        $bill5 = Bill::create([
            "name" => "BPI"
        ]);
        $bill6 = Bill::create([
            "name" => "MetroBank"
        ]);
        $bill7 = Bill::create([
            "name" => "AMA"
        ]);
        $bill8 = Bill::create([
            "name" => "NBI"
        ]);
        $bill9 = Bill::create([
            "name" => "Home Credit"
        ]);
    }
}
