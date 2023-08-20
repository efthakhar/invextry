<?php

namespace Database\Seeders;

use App\Models\Accounting\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public $accounts = [
        [
            'id' => 1,
            'name' => 'General Account',
            'bank_name' => 'HSBC Bank',
            'branch_name' => 'Dhaka Branch',
            'account_number' => '984932489384298',
            'balance' => 0,
            'status' => 'active',
            'details' => 'This Account is used for all general transaction of inventory',
        ],
        [
            'id' => 2,
            'name' => 'Income Expense Handler Account',
            'bank_name' => 'Asian Bank',
            'branch_name' => 'Chittagong Branch',
            'account_number' => '36493200334294',
            'balance' => 0,
            'status' => 'active',
            'details' => 'This Account is used for all general transaction of inventory',
        ],
        [
            'id' => 3,
            'name' => "Local Supplier's Paying Account",
            'bank_name' => 'Jamuna Bank',
            'branch_name' => 'Sylet Branch',
            'account_number' => '33458220330244',
            'balance' => 0,
            'status' => 'disabled',
            'details' => '',
        ],
    ];

    public function run(): void
    {
        foreach ($this->accounts as $account) {
            Account::create($account);
        }
    }
}
