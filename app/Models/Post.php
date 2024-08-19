<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class Post extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;
    protected $fillable = [
        'image',
        'icon',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'post_catalogue_id',
    ];

    protected $table = 'posts';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_language', 'language_id', 'post_id')
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

    public function post_catalogues()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }

    
}   
