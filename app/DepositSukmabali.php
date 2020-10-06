<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositSukmabali extends Model
{
    protected $table = 'deposit_sukmabali';
    protected $fillable = [
        'transaction_item_id',
        'persen',
        'total_deposit',
        'status'
    ];
}
