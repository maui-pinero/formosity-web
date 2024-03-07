<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Product extends Model
{
    use HasFactory, Favoriteable;
    
    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'sku',
        'mrp',
        'selling_price',
        'stock'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function image(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function oldestImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->oldestOfMany();
    }
}
