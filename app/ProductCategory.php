<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'product_category_id';
    protected $fillable = [
        'name',
        'photo_url',
        'description',
    ];
}
