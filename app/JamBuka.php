<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JamBuka extends Model
{
    protected $table = 'jam_buka';
    protected $fillable = [
        'produsen_id',
        'hari',
        'status',
        'jam_buka',
        'jam_tutup'
    ];
}
