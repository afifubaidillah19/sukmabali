<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KuponGroupProdusen extends Model
{
    protected $table = 'kupon_group_produsen';
    protected $fillable = [
        'produsen_id',
        'kupon_group_id',
    ];
}
