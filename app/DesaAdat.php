<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesaAdat extends Model
{
    protected $table = 'desa_adat';
    protected $fillable = [
        'nama',
        'alamat',
        'no_telp',
        'nama_kepala_desa',
        'kecamatan_id',
        'status'
    ];

    public function kecamatan(){
        return $this->belongsTo(Kecamatan::class,'kecamatan_id','id');
    }
}
