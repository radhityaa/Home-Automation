<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'route'];
    protected $with = ['publishers'];

    public function publishers()
    {
        return $this->hasMany(Publisher::class);
    }
}
