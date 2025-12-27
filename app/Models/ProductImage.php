<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;   
use App\Models\Product;

class ProductImage extends Model
{
    use HasFactory;


    protected $fillable = ['product_id','path','public_id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

