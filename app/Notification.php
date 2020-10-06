<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'notification_id';
    protected $fillable = [
        'notification_type',
        'sender_id',
        'receiver_id',
        'message',
        'title',
        'notification_status',
        'priority'
    ];
}
