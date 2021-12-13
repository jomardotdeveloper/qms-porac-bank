<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];

        foreach($branch_ids as $id){
            // BRANCH 1
            $manager = Role::create([
                "name" => "Manager",
                "branch_id" => $id
            ]);
            $manager->permissions()->sync([6, 2, 9]);

            $teller = Role::create([
                "name" => "Teller",
                "branch_id" => $id
            ]);
            $teller->permissions()->sync([1,6]);

            $server = Role::create([
                "name" => "Server",
                "branch_id" => $id
            ]);
            $server->permissions()->sync([8]);

            $server = Role::create([
                "name" => "IT",
                "branch_id" => $id
            ]);
            $server->permissions()->sync([4, 6, 7]);
        }

        

    }
}
