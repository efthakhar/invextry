<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'amount',
        'type',
        'date',
        'note',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
