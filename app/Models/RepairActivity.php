<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'action', // booked, shipment_created, step_added, status_changed
        'description',
        'user_id',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
