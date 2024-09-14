<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Language extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'canonical',
        'publish',
        'user_id',
        'image',
        'current',
    ];

    protected $table = 'languages';


    public function post_catalogues()
    {
        return $this->belongsToMany(
            PostCatalogue::class,
            'post_catalogue_language',
            'language_id',
            'post_catalogue_id'
        )
            ->withPivot(
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'post_language',
            'language_id',
            'post_id'
        )
            ->withPivot(
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function product_catalogues()
    {
        return $this->belongsToMany(
            ProductCatalogue::class,
            'product_catalogue_language',
            'language_id',
            'product_catalogue_id'
        )
            ->withPivot(
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_language',
            'language_id',
            'product_id'
        )
            ->withPivot(
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function attribute_catalogues()
    {
        return $this->belongsToMany(
            AttributeCatalogue::class,
            'attribute_catalogue_language',
            'language_id',
            'attribute_catalogue_id'
        )
            ->withPivot(
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'product_language',
            'language_id',
            'product_id'
        )
            ->withPivot(
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function product_variants()
    {
        return $this->belongsToMany(
            Product::class,
            'product_variant_language',
            'language_id',
            'product_variant_id'
        )->withPivot('name')->withTimestamps();
    }
}
