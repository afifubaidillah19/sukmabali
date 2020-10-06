<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kupon extends Model
{
    protected $table = 'kupon';
    protected $fillable = [
        'kupon_group_id',
        'code',
        'status',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
