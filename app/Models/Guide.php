<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    protected $fillable = [
        'device', 'category', 'brand', 'series', 'model', 'issue'
    ];

    public function resources()
    {
        return $this->hasMany(GuideResource::class);
    }
}

