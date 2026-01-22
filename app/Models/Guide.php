<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GuideResource; 
use App\Models\Review;

class Guide extends Model
{
    protected $fillable = [
        'device', 'category', 'brand', 'series', 'model', 'issue',
    'issue_slug','youtube_url',
    ];

    public function resources()
    {
        // Now GuideResource is correctly referenced
        return $this->hasMany(GuideResource::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}