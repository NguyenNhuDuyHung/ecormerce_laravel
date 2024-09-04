<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class {ModuleTemplate} extends Model
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

    protected $table = '{tableName}';

    public function languages()
    {
        return $this->belongsToMany(Language::class, '{pivotTable}', '{foreignKey}', 'language_id')
            ->withPivot(
                '{foreignKey}',
                'language_id',
                'name',
                'description',
                'content',
                'meta_title',
                'meta_description',
                'meta_keyword',
                'canonical',
            )->withTimestamps();
    }

    public function {module}_language()
    {
        return $this->hasMany({pivotModel}::class, '{foreignKey}', 'id')->where('language_id', '=', app()->getLocale());
    }

    public static function isNodeCheck($id = 0)
    {
        ${relation}Catalogue = {ModuleTemplate}::find($id);

        if (${relation}Catalogue->rgt - ${relation}Catalogue->lft !== 1) {
            return false;
        }

        return true;
    }

    public function {relation}s()
    {
        return $this->belongsToMany({relationModel}::class, '{relationPivot}', '{foreignKey}', '{relation}_id');
    }
}
