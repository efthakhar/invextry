<?php

namespace Database\Seeders;

use App\Services\PurchaseService;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public $purchases = [

        [
            'invoice_status' => 'ordered',
            'payment_status' => 'unpaid',
            'invoice_date' => '2023-08-09',
            'invoice_note' => 'New Purchase Order .',
            'supplier_id' => 20,
            'warehouse_id' => 1,
            'discount' => 100,
            'shipping_cost' => 50,
            'invoice_tax_rate' => 2,
            'products' => [
                [
                    'id' => 1,
                    'quantity' => 5,
                ],
                [
                    'id' => 2,
                    'quantity' => 1,
                ],
            ],

        ],

        [
            'invoice_status' => 'ordered',
            'payment_status' => 'unpaid',
            'invoice_date' => '2023-08-09',
            'invoice_note' => 'New Purchase Order .',
            'supplier_id' => 7,
            'warehouse_id' => 1,
            'discount' => 100,
            'shipping_cost' => 130,
            'invoice_tax_rate' => 1.3,
            'products' => [
                [
                    'id' => 1,
                    'quantity' => 25,
                ],
                [
                    'id' => 2,
                    'quantity' => 7,
                ],
            ],

        ],
        [
            'invoice_status' => 'ordered',
            'payment_status' => 'unpaid',
            'invoice_date' => '2023-08-19',
            'invoice_note' => 'New Purchase Order .',
            'supplier_id' => 7,
            'warehouse_id' => 2,
            'discount' => 100,
            'shipping_cost' => 130,
            'invoice_tax_rate' => 2.7,
            'products' => [
                [
                    'id' => 2,
                    'quantity' => 125,
                ],
                [
                    'id' => 3,
                    'quantity' => 17,
                ],
            ],

        ],

    ];

    public function run(): void
    {
        $purchaseService = new PurchaseService();

        foreach ($this->purchases as $purchase) {
            $purchaseService->create($purchase);
        }
    }
}
