<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatuanProduk extends Model
{
    protected $table = 'satuan_produk';
    protected $fillable = [
        'nama',
        'status '
        
    ];
}
