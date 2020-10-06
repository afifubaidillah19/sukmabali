<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';
    protected $fillable = [
        'nama',
        'deskripsi',
        'photo_path',
        'user_id',
        'type_user',
        'status'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function bupda(){
        return $this->belongsTo(Bupda::class, 'user_id', 'id');
    }
}
