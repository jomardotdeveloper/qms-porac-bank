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
            $manager->permissions()->sync([2, 4, 5, 6, 7]);

            $teller1 = Role::create([
                "name" => "Teller 1",
                "branch_id" => $id
            ]);
            $teller1->permissions()->sync([1,6]);
        }

        

    }
}
