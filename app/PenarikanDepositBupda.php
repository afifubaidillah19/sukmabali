<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenarikanDepositBupda extends Model
{
    protected $table = 'penarikan_deposit_bupda';
    protected $fillable = [
        'status'
    ];
    protected $casts = [
        'tmp' => 'json',
    ];
    public function detail_penarikan_deposit_bupda_list(){
        return $this->hasMany(DetailPenarikanDepositBupda::class, 'penarikan_deposit_bupda_id','id');
    }
}
