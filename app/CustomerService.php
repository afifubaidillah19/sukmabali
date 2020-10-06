<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    protected $table = 'customer_service';

    protected $fillable = [
        'user_id',
        'desa_adat_id',
        'bupda_id',
        'nama',
        'photo_path',
        'photo_ktp_path',
        'status',
        'alamat',
        'latitude',
        'longitude',
        'no_telepon',
        'email',
        'deskripsi'
    ];

    public function bupda(){
        return $this->belongsTo(Bupda::class,'bupda_id','id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function desa_adat(){
        return $this->belongsTo(DesaAdat::class,'desa_adat_id','id');
    }
}
