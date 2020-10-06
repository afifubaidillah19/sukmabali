<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'review_id';
    protected $fillable = [
        'transaction_item_id',
        'rating',
        'review',
        'review_status'
    ];
}
