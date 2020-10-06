<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositBupda extends Model
{
    protected $table = 'deposit_bupda';
    protected $fillable = [
        'bupda_id',
        'transaction_item_id',
        'persen',
        'total_deposit',
        'status'
    ];
}
