<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $attributes = [
        'sale_due' => 0,
        'sale_return_due' => 0,
        'purchase_due' => 0,
        'purchase_return_due' => 0,
    ];
}
