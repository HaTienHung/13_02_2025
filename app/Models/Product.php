<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="price", type="number", format="float"),
 *     @OA\Property(property="category_id", type="integer"),
 *     @OA\Property(property="image", type="string", format="binary")
 * )
 */
class Product extends Model
{
    use HasFactory;
    use FilterTrait;

    protected $fillable = ['name', 'slug', 'price', 'description', 'category_id','image','image_url'];


//    protected $appends = ['image_url'];
//
//    public function getImageUrlAttribute()
//    {
//        // Kiểm tra xem có tồn tại URL từ Cloudinary không
//        return $this->image ? $this->image : null; // Trả về URL đã lưu trong cột 'image'
//    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
