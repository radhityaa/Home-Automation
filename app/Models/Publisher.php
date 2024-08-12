<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'topic', 'device_id', 'on_message', 'off_message'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
