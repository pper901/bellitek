<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseSetting extends Model
{
    protected $fillable = ['user_id', 'name', 'address_code','email', 'phone', 'address', 'latitude', 'longitude'];
}

