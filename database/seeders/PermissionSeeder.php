<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission1 = Permission::create(["name" => "Control Access", "code_name" => "CA"]);
        $permission2 = Permission::create(["name" => "Report Access", "code_name" => "RA"]);
        $permission3 = Permission::create(["name" => "Role Access", "code_name" => "RLA"]);
        $permission4 = Permission::create(["name" => "User Access", "code_name" => "UA"]);
        $permission5 = Permission::create(["name" => "Account Access", "code_name" => "AA"]);
        $permission6 = Permission::create(["name" => "Dashboard Access", "code_name" => "DA"]);
        $permission7 = Permission::create(["name" => "Setting Access", "code_name" => "SA"]);
        $permission8 = Permission::create(["name" => "Server Access", "code_name" => "SVA"]);
        $permission9 = Permission::create(["name" => "Team Access", "code_name" => "TA"]);
    }
}
