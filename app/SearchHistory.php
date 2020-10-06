<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = 'search_history';
    protected $primaryKey = 'search_history_id';
    protected $fillable = [
        'user_id',
        'keyword',
        'keyword_category',
        'latitude',
        'longitude'
    ];
}
