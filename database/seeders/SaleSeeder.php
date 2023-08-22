<?php

namespace Database\Seeders;

use App\Services\PaymentService;
use App\Services\SaleService;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public $currentDate;

    public function __construct() 
    {
        $this->currentDate = Carbon::now();
    }

    public $sales = [

        [
            'invoice_status' => 'pending',
            'paid_amount' => 8691.25,
            'invoice_date' => '2023-08-09',
            'note' => 'New Sale Order .',
            'party_id' => 1,
            'warehouse_id' => 1,
            'discount' => 0,
            'shipping_cost' => 0,
            'invoice_tax_rate' => 0,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 10,
                ],
                [
                    'id' => 2,
                    'quantity' => 15,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'cash',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'ordered',
            'paid_amount' => 6184.5,
            'invoice_date' => '2023-08-09',
            'note' => 'New Sale Order .',
            'party_id' => 2,
            'warehouse_id' => 1,
            'discount' => 100,
            'shipping_cost' => 130,
            'invoice_tax_rate' => 1.3,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 15,
                ],
                [
                    'id' => 2,
                    'quantity' => 3,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'cash',
            'payment_note' => ' ',

        ],
        [
            'invoice_status' => 'received',
            'paid_amount' => 0,
            'invoice_date' => '2023-08-19',
            'note' => 'New Sale Order .',
            'party_id' => 3,
            'warehouse_id' => 2,
            'discount' => 100,
            'shipping_cost' => 130,
            'invoice_tax_rate' => 0,
            'items' => [
                [
                    'id' => 2,
                    'quantity' => 4,
                ],
                [
                    'id' => 3,
                    'quantity' => 4,
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
            'note' => 'New Sale Order .',
            'party_id' => 6,
            'warehouse_id' => 2,
            'discount' => 0,
            'shipping_cost' => 100,
            'invoice_tax_rate' => 2,
            'items' => [
                [
                    'id' => 3,
                    'quantity' => 14,
                ],
                [
                    'id' => 2,
                    'quantity' => 18,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'bank',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'pending',
            'paid_amount' => 19480,
            'invoice_date' => '2023-08-19',
            'note' => 'New Sale Order .',
            'party_id' => 13,
            'warehouse_id' => 2,
            'discount' => 0,
            'shipping_cost' => 100,
            'invoice_tax_rate' => 2,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 54,
                ],
                [
                    'id' => 2,
                    'quantity' => 8,
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
            'note' => 'New Sale Order .',
            'party_id' => 15,
            'warehouse_id' => 1,
            'discount' => 1570,
            'shipping_cost' => 4630,
            'invoice_tax_rate' => 2.7,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 14,
                ],
                [
                    'id' => 2,
                    'quantity' => 35,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'payoneer',
            'payment_note' => ' ',

        ],

        [
            'invoice_status' => 'received',
            'paid_amount' => 0,
            'invoice_date' => '2023-08-19',
            'note' => 'New Sale Order .',
            'party_id' => 19,
            'warehouse_id' => 2,
            'discount' => 170,
            'shipping_cost' => 4630,
            'invoice_tax_rate' => 2,
            'items' => [
                [
                    'id' => 1,
                    'quantity' => 34,
                ],
                [
                    'id' => 3,
                    'quantity' => 25,
                ],
            ],
            'account_id' => 1,
            'payment_method' => 'check',
            'payment_note' => ' ',

        ],

    ];

    public function run(): void
    {
        $iterator       = 0;
        foreach ($this->sales as $sale) {
            $sale['invoice_date'] = $this->currentDate->startOfWeek()->copy()->addDays($iterator++);;
            (new SaleService(new StockService(), new PaymentService))->create($sale);
        }
    }
}
