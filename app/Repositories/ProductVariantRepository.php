<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class ProductVariantService
 * @package App\Services
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model;
    public function __construct(
        ProductVariant $model
    ) {
        $this->model = $model;
    }
}
