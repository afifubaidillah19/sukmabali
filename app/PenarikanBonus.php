<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenarikanBonus extends Model
{
    protected $table = 'penarikan_bonus';
    protected $fillable = [
        'user_id',
        'nama_bank',
        'nama_pemilik_rekening',
        'no_rekening',
        'amount',
        'status',
        'foto_bukti_transfer'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
