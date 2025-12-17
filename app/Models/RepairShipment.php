<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'type', // dropoff | pickup
        'courier',
        'tracking_code',
        'tracking_url',
        'status', // pending | in_transit | delivered
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}
