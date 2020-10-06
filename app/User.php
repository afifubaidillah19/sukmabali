<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'kode_upline',
        'parent_id',
        'gen_no',
        'email', 
        'ktp_path',
        'password',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'firebase_user_id',
        'desa_adat_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function desa_adat(){
        return $this->belongsTo(DesaAdat::class, 'desa_adat_id','id');
    }
}
