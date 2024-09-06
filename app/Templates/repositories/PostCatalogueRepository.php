<?php

namespace App\Repositories;

use App\Models\{$class}Catalogue;
use App\Repositories\Interfaces\{$class}CatalogueRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class {$class}CatalogueRepository extends BaseRepository implements {$class}CatalogueRepositoryInterface
{
    protected $model;

    public function __construct(
        {$class}Catalogue $model
    ){
        $this->model = $model;
    }

    

    public function get{$class}CatalogueById(int $id = 0, $language_id = 0){
        return $this->model->select([
                '{module}_catalogues.id',
                '{module}_catalogues.parent_id',
                '{module}_catalogues.image',
                '{module}_catalogues.icon',
                '{module}_catalogues.album',
                '{module}_catalogues.publish',
                '{module}_catalogues.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('{module}_catalogue_language as tb2', 'tb2.{module}_catalogue_id', '=','{module}_catalogues.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
