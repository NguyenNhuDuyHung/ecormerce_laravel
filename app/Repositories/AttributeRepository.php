<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\Interfaces\AttributeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;

    public function __construct(
        Attribute $model
    ){
        $this->model = $model;
    }

    

    public function getAttributeById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'attributes.id',
                'attributes.attribute_catalogue_id',
                'attributes.image',
                'attributes.icon',
                'attributes.album',
                'attributes.publish',
                'attributes.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('attribute_language as tb2', 'tb2.attribute_id', '=','attributes.id')
        ->with('attribute_catalogues')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
