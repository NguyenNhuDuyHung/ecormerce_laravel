<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class AttributeCatalogueRepository extends BaseRepository implements AttributeCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(
        AttributeCatalogue $model
    ){
        $this->model = $model;
    }

    

    public function getAttributeCatalogueById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'attribute_catalogues.id',
                'attribute_catalogues.parent_id',
                'attribute_catalogues.image',
                'attribute_catalogues.icon',
                'attribute_catalogues.album',
                'attribute_catalogues.publish',
                'attribute_catalogues.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('attribute_catalogue_language as tb2', 'tb2.attribute_catalogue_id', '=','attribute_catalogues.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
