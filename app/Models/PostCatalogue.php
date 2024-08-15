<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCatalogue extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_catalogue_language', 'post_catalogue_id', 'language_id')
            ->withPivot(
                'name',
                'description',
                'content',
                'meta_title',
                'meta_description',
                'meta_keyword',
                'canonical',
            )->withTimestamps();
    }

    public function post_catalogue_language() {
        return $this->hasMany(PostCatalogueLanguage::class,'post_catalogue_id','id');
    }
}
