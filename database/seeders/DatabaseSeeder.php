<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            ServiceSeeder::class,
            BranchSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AccountSeeder::class,
            LoanSeeder::class,
            BillSeeder::class,
            LogSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
