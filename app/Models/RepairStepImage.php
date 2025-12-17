<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairStepImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_step_id',
        'image_path',
    ];

    public function step()
    {
        return $this->belongsTo(RepairStep::class);
    }
}
