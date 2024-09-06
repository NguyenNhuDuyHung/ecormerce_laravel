<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(
        Product $model
    ){
        $this->model = $model;
    }

    

    public function getProductById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'products.id',
                'products.product_catalogue_id',
                'products.image',
                'products.icon',
                'products.album',
                'products.publish',
                'products.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('product_language as tb2', 'tb2.product_id', '=','products.id')
        ->with('product_catalogues')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
