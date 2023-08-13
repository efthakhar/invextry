<?php

namespace Database\Seeders;

use App\Models\Setting\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public $taxes = [
        ['id' => 1, 'name' => '1%vat', 'rate' => '1'],
        ['id' => 2, 'name' => '2.55%tax', 'rate' => '2.55'],
        ['id' => 3, 'name' => '14.5%localTax', 'rate' => '14.5'],
        ['id' => 4, 'name' => 'vat@5%', 'rate' => '5'],
        ['id' => 5, 'name' => '4% transit tax', 'rate' => '4.00'],
    ];

    public function run(): void
    {
        foreach ($this->taxes as $tax) {
            Tax::create($tax);
        }
    }
}
