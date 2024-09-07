<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class AttributeCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

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

    protected $table = 'attribute_catalogues';

    public function languages(){
        return $this->belongsToMany(Language::class, 'attribute_catalogue_language' , 'attribute_catalogue_id', 'language_id')
        ->withPivot(
            'attribute_catalogue_id',
            'language_id',
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();
    }

    public function attributes(){
        return $this->belongsToMany(Attribute::class, 'attribute_catalogue_attribute' , 'attribute_catalogue_id', 'attribute_id');
    }


    public function attribute_catalogue_language(){
        return $this->hasMany(AttributeCatalogueLanguage::class, 'attribute_catalogue_id', 'id')->where('language_id','=',1);
    }

    public static function isNodeCheck($id = 0){
        $attributeCatalogue = AttributeCatalogue::find($id);

        if($attributeCatalogue->rgt - $attributeCatalogue->lft !== 1){
            return false;
        } 

        return true;
        
    }



}
