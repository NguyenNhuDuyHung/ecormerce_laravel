<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'language_id',
        'name',
    ];

    protected $table = 'product_variant_language';

    public $timestamps = true;
}
