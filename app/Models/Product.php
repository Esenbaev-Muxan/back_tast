<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Events\ProductUpdated;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Schema(
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(property="id", type="integer", description="ID of the product", example=1),
 *     @OA\Property(property="title", type="string", description="Title of the product", example="Product Title"),
 *     @OA\Property(property="slug", type="string", description="Slug for the product", example="product-title"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the product", example=99.99),
 *     @OA\Property(property="eID", type="integer", description="External ID of the product", example=12345)
 * )
 */

class Product extends Model
{

    use HasFactory;
    protected $fillable = ['title', 'slug', 'price', 'eID'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public static function boot()
    {
        parent::boot();

        // Создание и обновление slug
        static::creating(function ($product): void {
            $product->slug = Str::slug($product->title);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->title);
        });

        // Отправка события после сохранения
        static::saved(function ($product) {
            event(new ProductUpdated($product)); // Генерация события
        });

         // Отправка события после сохранения
         static::saved(function ($product) {
            // Очистка кеша после изменения товара
            Cache::forget('popular-products-cache');
            
            // Генерация события
            event(new ProductUpdated($product)); 
        });

        // Очистка кеша при удалении товара
        static::deleted(function ($product) {
            Cache::forget('popular-products-cache');
        });
    }
}

