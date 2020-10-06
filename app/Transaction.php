<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'user_id',
        'merchant_id',
        'transaction_status',
        'bupda_id',
        'message',
        'delivery_address',
        'delivery_address_spesific',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_fee',
        'total_price',
        'total_payment'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bupda(){
        return $this->belongsTo(Bupda::class, 'bupda_id', 'id');
    }

    public function transaction_item(){
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }
}
