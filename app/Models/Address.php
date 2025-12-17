<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'street', 'city', 'state', 'country', 'postal_code','phonenumber',
    ];
}
