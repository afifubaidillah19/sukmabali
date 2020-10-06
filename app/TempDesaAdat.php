<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempDesaAdat extends Model
{
    protected $table = 'temp_desa_adat';
    protected $fillable = [
        'nama',
        'user_id',
        'type_user'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function bupda(){
        return $this->belongsTo(Bupda::class, 'user_id', 'id');
    }
    public function desa_adat(){
        return $this->belongsTo(DesaAdat::class, 'desa_adat_id', 'id');
    }
}
