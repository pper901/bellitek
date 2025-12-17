<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
    use HasFactory;

    protected $fillable = ['name','identifier','cost_per_call','notes'];

    public function calls()
    {
        return $this->hasMany(ApiCall::class);
    }
}
