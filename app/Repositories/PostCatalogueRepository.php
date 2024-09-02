<?php

namespace App\Repositories;

use App\Models\PostCatalogue;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;

/**
 * Class UserService
 * @package App\Services
 */
class PostCatalogueRepository extends BaseRepository implements PostCatalogueRepositoryInterface
{
    protected $model;
    public function __construct(PostCatalogue $model)
    {
        $this->model = $model;
    }

    public function getPostCatalogueById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
            'post_catalogues.id',
            'post_catalogues.parent_id',
            'post_catalogues.publish',
            'post_catalogues.image',
            'post_catalogues.icon',
            'post_catalogues.album',
            'post_catalogues.level',
            'post_catalogues.order',
            'post_catalogues.follow',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.meta_title',
            'tb2.meta_description',
            'tb2.meta_keyword',
            'tb2.canonical',
        ])->join(
                'post_catalogue_language as tb2',
                'tb2.post_catalogue_id',
                '=',
                'post_catalogues.id'
            )
            ->where(
                'tb2.language_id',
                '=',
                $language_id
            )->find($id);
    }
}
