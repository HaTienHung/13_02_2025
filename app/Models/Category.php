<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 * )
 */
class Category extends Model
{
    use HasFactory;
    use FilterTrait;

    protected $fillable = ['name','slug'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
