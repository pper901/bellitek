<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Repair;
use App\Models\WarehouseSetting; // <-- Import the new model

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    
    protected static function booted()
    {
        static::created(function ($user) {
            if (User::count() == 1) {
                $user->is_admin = true;
                $user->save();
            }
        });
    }

    // ------------------------------------
    // ADDED RELATIONSHIP FOR WAREHOUSE SETTINGS
    // ------------------------------------
    public function warehouseSetting()
    {
        // Assuming a one-to-one relationship where the warehouse_settings table 
        // has a user_id foreign key pointing to this user.
        return $this->hasOne(WarehouseSetting::class);
    }
    
    // ------------------------------------
    // Existing Relationships
    // ------------------------------------
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    
    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}