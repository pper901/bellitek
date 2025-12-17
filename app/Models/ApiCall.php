<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    use HasFactory;

    protected $fillable = ['api_provider_id','order_id','endpoint','count','cost_cached','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function provider()
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}