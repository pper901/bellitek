<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;   // ← ADD THIS
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
    'type','category','brand','name','slug','description','specification',
    'content','stock','price','purchase_price','weight','condition','status','user_id'
    ];


    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
            $product->slug = Str::slug($product->name . '-' . uniqid());
            }
        });
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

}

