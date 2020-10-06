<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositCS extends Model
{
    protected $table = 'deposit_cs';
    protected $fillable = [
        'cs_id',
        'transaction_item_id',
        'persen',
        'total_deposit',
        'status'
    ];
}
