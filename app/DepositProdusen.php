<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositProdusen extends Model
{
    protected $table = 'deposit_produsen';

    protected $fillable = [
        'produsen_id',
        'transaction_item_id',
        'total_deposit',
        'foto_bukti_transfer',
        'status'
    ];

    public function produsen(){
        return $this->belongsTo(Produsen::class, 'produsen_id', 'id');
    }
}
