<?php

namespace App\Models;

use App\Models\Accounting\Account;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'account_id',
        'invoice_id',
        'invoice_type',
        'payment_method',
        'amount',
        'date',
        'note',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
