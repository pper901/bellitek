<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents an individual update or step in the repair process timeline.
 * Linked to the 'repair_steps' table.
 */
class RepairStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_id',
        'title',
        'description',
        'engineer_id', // Links to User ID
    ];

    /**
     * Get the repair this step belongs to.
     */
    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    /**
     * Get the user (who is the engineer/admin) that created this step.
     */
    public function engineer()
    {
        // This links the engineer_id column to the primary key (id) of the User model.
        return $this->belongsTo(User::class, 'engineer_id');
    }

    /**
     * Get the images/photos associated with this repair step.
     * Assumes a 'repair_step_id' foreign key on the 'repair_step_images' table.
     */
    public function images()
    {
        return $this->hasMany(RepairStepImage::class);
    }
}