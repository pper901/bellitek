<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_code',
        'customer_name',
        'device_type',
        'brand',
        'issue',
        'contact',
        'delivery_method', // dropoff | pickup
        'status', // pending | received | diagnosing | repairing | completed
        'selected_rate',
    ];

    /* ================= Relationships ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(RepairStep::class);
    }

    public function shipment()
    {
        return $this->hasOne(RepairShipment::class);
    }
}
