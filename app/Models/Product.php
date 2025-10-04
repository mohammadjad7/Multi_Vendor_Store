<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "image",
        "price",
        "compare_price",
        "quantity",
        "category_id",
        "store_id",
        "status",
    ];
    //
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class, // Related Model
            "product_tag", // Pivot table name
            "product_id", // Fk in pivot table for the current model
            "tag_id", // Fk in pivot table for the related model
            'id', // Pk current model
            'id', // Pk related model
        );
    }
    protected static function booted()
    {
        static::addGlobalScope('store', new StoreScope());
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
