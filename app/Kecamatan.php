<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $fillable = [
        'kabupaten_id',
        'nama',
        'status'
    ];

    public function kabupaten(){
        return $this->belongsTo(Kecamatan::class, 'kabupaten_id', 'id');
    }
}
