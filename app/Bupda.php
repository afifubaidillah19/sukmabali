<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bupda extends Model
{
    protected $table = 'bupda';

    protected $fillable = [
        'bupda_name',
        'desa_adat_id',
        'phone_number',
        'address',
        'description',
        'verification_photo',
        'photo_path',
        'user_id',
        'latitude',
        'longitude',
        'no_rekening',
        'nama_bank',
        'an',
        'auto_verification_order'
    ];

    public function desa_adat(){
        return $this->belongsTo(DesaAdat::class, 'desa_adat_id','id');
    }
}
