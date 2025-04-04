<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="total_price", type="number",format="float"),
 *     @OA\Property(property="status", type="string"),
 * )
 */
class Order extends Model
{
    use HasFactory;
    use FilterTrait;

    protected $fillable = ['user_id', 'status', 'total_price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
