<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GuideResource; 

class Guide extends Model
{
    protected $fillable = [
        'device', 'category', 'brand', 'series', 'model', 'issue'
    ];

    public function resources()
    {
        // Now GuideResource is correctly referenced
        return $this->hasMany(GuideResource::class);
    }
}