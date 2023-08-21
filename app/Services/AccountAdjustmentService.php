<?php

namespace App\Services;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountAdjustment;

class AccountAdjustmentService
{
    public function __construct()
    {

    }

    public function createAdjustment(array $adjustment)
    {
        AccountAdjustment::create($adjustment);
        $account = Account::where('id', $adjustment['account_id']);

        if ($adjustment['type'] == 'add') {
            $account->increment('balance', $adjustment['amount']);
        } elseif ($adjustment['type'] == 'subtract') {
            $account->decrement('balance', $adjustment['amount']);
        }
    }
}
