<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CreateApp extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
        ]);
    }
}
