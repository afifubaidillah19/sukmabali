<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KuponGroup extends Model
{
    protected $table = 'kupon_group';
    protected $fillable = [
        'name',
        'description',
        'foto_path',
        'expired',
        'total',
        'amount_per_kupon'
    ];

    public function produsens(){
        return $this->belongsToMany(Produsen::class)->withTimestamps();
    }

    public function kupon(){
        return $this->hasMany(Kupon::class,'kupon_group_id');
    }
}
