<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug'];
    protected $with = ['publishers'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function publishers()
    {
        return $this->hasMany(Publisher::class);
    }
}
