<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     required={"title", "slug", "eID"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the category"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The title of the category"
 *     ),
 *     @OA\Property(
 *         property="slug",
 *         type="string",
 *         description="The URL-friendly version of the category title"
 *     ),
 *     @OA\Property(
 *         property="eID",
 *         type="integer",
 *         description="The external ID for the category"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp of when the category was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp of when the category was last updated"
 *     )
 * )
 */
class Category extends Model
{
    protected $fillable = ['title', 'slug', 'eID'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->title);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->title);
        });
    }
}
