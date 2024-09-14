<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'code',
        'quantity',
        'sku',
        'price',
        'barcode',
        'file_name',
        'file_url',
        'album',
        'publish',
        'user_id',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function languages()
    {
        return $this->belongsToMany(
            Language::class,
            'product_variant_language',
            'product_variant_id',
            'language_id'
        )->withPivot(
                'name',
            )->withTimestamps();
    }
}
