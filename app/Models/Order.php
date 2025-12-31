<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    protected $fillable = [
    'user_id',
    'payment_reference',
    'shipping_amount',
    'items_total',
    'grand_total',
    'customer_name',
    'customer_phone',
    'customer_email',
    'address_line',
    'city',
    'state',
    'country',
    'payment_status',
    'order_status',
    'tracking_code',
    'tracking_url',
    'request_token',
    'courier_id',    // Add this
    'service_code',  // Add this
    'label_url',
];


    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function setOrderStatusAttribute($value)
    {
        $this->attributes['order_status'] = $value;

        // Check if the status being set is 'delivered'
        if ($value === 'delivered') {
            // Clear the tracking code
            $this->attributes['tracking_code'] = null;
        }
    }
}
