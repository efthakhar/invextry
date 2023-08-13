<?php

namespace Database\Seeders;

use App\Models\Product\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public $units = [
        ['id' => 1, 'name' => 'Kilogram', 'short_name' => 'kg', 'base_unit_id' => null, 'operator' => null, 'operation_value' => null],
        ['id' => 2, 'name' => 'Grams', 'short_name' => 'g', 'base_unit_id' => 1, 'operator' => 'divide', 'operation_value' => 1000],
        ['id' => 3, 'name' => 'Meter', 'short_name' => 'm', 'base_unit_id' => null, 'operator' => null, 'operation_value' => null],
        ['id' => 4, 'name' => 'Centimeter', 'short_name' => 'cm', 'base_unit_id' => 3, 'operator' => 'divide', 'operation_value' => 100],
        ['id' => 5, 'name' => 'Liter', 'short_name' => 'l', 'base_unit_id' => null, 'operator' => null, 'operation_value' => null],
        ['id' => 6, 'name' => 'Mili Liter', 'short_name' => 'ml', 'base_unit_id' => 5, 'operator' => 'divide', 'operation_value' => 1000],
        ['id' => 7, 'name' => 'Piece', 'short_name' => 'pc', 'base_unit_id' => null, 'operator' => null, 'operation_value' => null],
        ['id' => 8, 'name' => 'Box', 'short_name' => 'box', 'base_unit_id' => null, 'operator' => null, 'operation_value' => null],
    ];

    public function run(): void
    {
        foreach ($this->units as $unit) {
            Unit::create([
                'name' => $unit['name'],
                'short_name' => $unit['short_name'],
                'base_unit_id' => $unit['base_unit_id'],
                'operator' => $unit['operator'],
                'operation_value' => $unit['operation_value'],
            ]);
        }
    }
}
