<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenarikanDepositCS extends Model
{
    protected $table = 'detail_penarikan_deposit_cs';
    protected $fillable = [
        'penarikan_deposit_cs_id',
        'deposit_cs_id'
    ];

    public function deposit_cs(){
        return $this->belongsTo(DepositCS::class,'deposit_cs_id','id');
    }
}
