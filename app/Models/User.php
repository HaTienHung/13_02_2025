<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\FilterTrait;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="phone_number", type="string"),
 * )
 */

class User extends Authenticatable
{
    static $admin = 1;
    static $user = 2;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, FilterTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'phone_number', 'password','address',
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
    public function scopeOfRole($query, $type)
    {
        return $query->where('role_id', $type);
    }
    public function scopeOfEmail($query, $type)
    {
        return $query->where('email', $type);
    }

    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

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
        ];
    }
}
