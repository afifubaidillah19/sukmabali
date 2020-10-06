<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenarikanDepositBupda extends Model
{
    protected $table = 'detail_penarikan_deposit_bupda';
    protected $fillable = [
        'penarikan_deposit_bupda_id',
        'deposit_bupda_id'
    ];

    public function deposit_bupda(){
        return $this->belongsTo(DepositBupda::class,'deposit_bupda_id','id');
    }
}
