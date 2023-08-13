<?php

namespace Database\Seeders;

use App\Models\Setting\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public $warehouses = [
        ['id' => 1, 'name' => 'warehouse 1', 'email' => 'warehouse1@invextry.com', 'phone' => '01900000000', 'address' => 'Road no 10, sector 5, F block'],
        ['id' => 2, 'name' => 'warehouse 2', 'email' => 'warehouse2@invextry.com', 'phone' => '01800000000', 'address' => 'Road no 40, sector 10, K block'],
    ];

    public function run(): void
    {
        foreach ($this->warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
