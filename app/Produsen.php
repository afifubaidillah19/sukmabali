<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produsen extends Model
{
    protected $table = "produsen";
    protected $fillable = [
        'bupda_id',
        'user_id',
        'produsen_name',
        'phone_number',
        'email',
        'address',
        'latitude',
        'longitude',
        'description',
        'verification_photo_path',
        'produsen_status',
        'delivery_fee_per_km'
    ];

    public function bupda(){
        return $this->belongsTo(Bupda::class,'bupda_id','id');
    }
}
