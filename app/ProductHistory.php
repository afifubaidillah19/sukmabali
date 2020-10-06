<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $table = 'product_history';
    protected $fillable = [
        'product_id',
        'transaction_item_id',
        'stok',
        'type',
        'photo_url',
        'status',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
