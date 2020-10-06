<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonus';
    protected $fillable = [
        'user_id',
        'transaction_id',
        'percentage',
        'amount'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function bupda(){
        return $this->belongsTo(Bupda::class, 'user_id', 'id');
    }
}
