<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenarikanDepositCS extends Model
{
    protected $table = 'penarikan_deposit_cs';
    protected $fillable = [
        'cs_id',
        'status'
    ];

    public function detail_penarikan_deposit_cs_list(){
        return $this->hasMany(DetailPenarikanDepositCS::class, 'penarikan_deposit_cs_id','id');
    }

    public function cs(){
        return $this->belongsTo(CustomerService::class, 'cs_id','id');
    }
}
