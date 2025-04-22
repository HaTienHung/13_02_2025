<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Inventory",
 *     type="object",
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="quantity", type="integer"),
 * )
 */
class InventoryTransaction extends Model
{
    use HasFactory;
    use FilterTrait;

    protected $fillable = ['product_id', 'type', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
