<?php

namespace App\Repositories;

use App\Models\{$class};
use App\Repositories\Interfaces\{$class}RepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class {$class}Repository extends BaseRepository implements {$class}RepositoryInterface
{
    protected $model;

    public function __construct(
        {$class} $model
    ){
        $this->model = $model;
    }

    

    public function get{$class}ById(int $id = 0, $language_id = 0){
        return $this->model->select([
                '{module}s.id',
                '{module}s.{module}_catalogue_id',
                '{module}s.image',
                '{module}s.icon',
                '{module}s.album',
                '{module}s.publish',
                '{module}s.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('{module}_language as tb2', 'tb2.{module}_id', '=','{module}s.id')
        ->with('{module}_catalogues')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
