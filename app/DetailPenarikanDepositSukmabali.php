<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenarikanDepositSukmabali extends Model
{
    protected $table = 'detail_penarikan_deposit_sukmabali';
    protected $fillable = [
        'penarikan_deposit_sukmabali_id',
        'deposit_sukmabali_id'
    ];

    public function deposit_sukmabali(){
        return $this->belongsTo(DepositSukmabali::class,'deposit_sukmabali_id','id');
    }
}
