<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mqtt extends Model
{
    use HasFactory;

    protected $fillable = ['host', 'port', 'client_id', 'username', 'password', 'clean_session', 'auto_reconnect'];
}
