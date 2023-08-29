<?php

namespace Database\Seeders;

use App\Services\PaymentService;
use App\Services\PurchaseService;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public $currentDate;

    public function __construct()
    {
        $this->currentDate = Carbon::now();
    }

    public $purchases = [

        [
            'invoice_status' => 'pending',
            'paid_amount' => 8691.25,
            'invoice_date' => '2023-08-09',
            'note' => 'New Purchase Order .',
            'party_id' => 20,
            'warehouse_id' => 1,
            'discount' => 0,
            'shipping_cost' => 0,
            'invoice_tax_rate' => 0,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 15,
                ],
                [
                    'id' => 2,
                    'quantity' => 8,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'cash',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'ordered',
            'paid_amount' => 2094.25,
            'invoice_date' => '2023-08-09',
            'note' => 'New Purchase Order .',
            'party_id' => 7,
            'warehouse_id' => 1,
            'discount' => 100,
            'shipping_cost' => 130,
            'invoice_tax_rate' => 1.3,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 6,
                ],
                [
                    'id' => 3,
                    'quantity' => 2,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'cash',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'received',
            'paid_amount' => 6000,
            'invoice_date' => '2023-08-19',
            'note' => 'New Purchase Order .',
            'party_id' => 7,
            'warehouse_id' => 2,
            'discount' => 100,
            'shipping_cost' => 130,
            'invoice_tax_rate' => 0,
            'items' => [
                [
                    'id' => 2,
                    'quantity' => 3,
                ],
                [
                    'id' => 3,
                    'quantity' => 2,
                ],
            ],
            'account_id' => 2,
            'payment_method' => 'cash',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'received',
            'paid_amount' => 0,
            'invoice_date' => '2023-08-19',
            'note' => 'New Purchase Order .',
            'party_id' => 11,
            'warehouse_id' => 2,
            'discount' => 0,
            'shipping_cost' => 100,
            'invoice_tax_rate' => 2,
            'items' => [
                [
                    'id' => 3,
                    'quantity' => 2,
                ],
                [
                    'id' => 2,
                    'quantity' => 3,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'bank',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'pending',
            'paid_amount' => 1350,
            'invoice_date' => '2023-08-19',
            'note' => 'New Purchase Order .',
            'party_id' => 14,
            'warehouse_id' => 2,
            'discount' => 0,
            'shipping_cost' => 100,
            'invoice_tax_rate' => 2,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 5,
                ],
                [
                    'id' => 2,
                    'quantity' => 5,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'payoneer',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'ordered',
            'paid_amount' => 4350,
            'invoice_date' => '2023-08-19',
            'note' => 'New Purchase Order .',
            'party_id' => 20,
            'warehouse_id' => 1,
            'discount' => 1570,
            'shipping_cost' => 4630,
            'invoice_tax_rate' => 2.7,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 5,
                ],
                [
                    'id' => 2,
                    'quantity' => 6,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'payoneer',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'received',
            'paid_amount' => 8000,
            'invoice_date' => '2023-08-19',
            'note' => 'New Purchase Order .',
            'party_id' => 20,
            'warehouse_id' => 2,
            'discount' => 170,
            'shipping_cost' => 4630,
            'invoice_tax_rate' => 2,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 5,
                ],
                [
                    'id' => 3,
                    'quantity' => 2,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'check',
            'payment_note' => ' ',

        ],

    ];

    public function run(): void
    {
        $iterator = 0;
        foreach ($this->purchases as $purchase) {
            $purchase['invoice_date'] = $this->currentDate->startOfWeek()->copy()->addDays($iterator++);
            (new PurchaseService(new StockService(), new PaymentService))->create($purchase);
        }
    }
}
