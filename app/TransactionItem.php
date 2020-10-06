<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $table = 'transaction_item';
    protected $primaryKey = 'transaction_item_id';
    protected $fillable = [
        'transaction_id',
        'product_id',
        'price',
        'quantity',
        'message',
        'transaction_status',
        'delivery_fee'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id','id');
    }

    public function deposit_cs(){
        return $this->belongsTo(DepositCS::class, 'transaction_item_id','transaction_item_id');
    }

    public function deposit_bupda(){
        return $this->belongsTo(DepositBupda::class, 'transaction_item_id','transaction_item_id');
    }

    public function deposit_sukmabali(){
        return $this->belongsTo(DepositSukmabali::class, 'transaction_item_id','transaction_item_id');
    }
}
