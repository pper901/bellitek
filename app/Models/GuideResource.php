<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Import the base Model class
use App\Models\Guide; // Import the related Guide model

class GuideResource extends Model
{
    protected $fillable = [
        'guide_id', 'cause', 'solution', 'details'
    ];

    /**
     * Get the guide that owns the resource.
     */
    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }
}