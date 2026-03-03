<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'title',
        'description',
        'uuid',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Automatically generate UUID on creation
     */

    /**
     * Lecturer (owner of the class)
     */
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}

