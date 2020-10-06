<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenarikanDepositSukmabali extends Model
{
    protected $table = 'penarikan_deposit_sukmabali';
    protected $fillable = [
        'status'
    ];
    protected $casts = [
        'tmp' => 'json',
    ];
    public function detail_penarikan_deposit_sukmabali_list(){
        return $this->hasMany(DetailPenarikanDepositSukmabali::class, 'penarikan_deposit_sukmabali_id','id');
    }
}
