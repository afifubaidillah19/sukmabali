<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "product";
    protected $fillable = [
        'produsen_id',
        'product_category_id',
        'satuan_produk_id',
        'name',
        'description',
        'photo_url',
        'stok',
        'price',
        'product_status'
    ];

    public function satuan_produk(){
        return $this->belongsTo(SatuanProduk::class, 'satuan_produk_id','id');
    }
    public function product_category(){
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    public function produsen(){
        return $this->belongsTo(Produsen::class, 'produsen_id', 'id');
    }
}
