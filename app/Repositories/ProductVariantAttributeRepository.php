<?php

namespace App\Repositories;

use App\Models\ProductVariantAttribute;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class ProductVariantService
 * @package App\Services
 */
class ProductVariantAttributeRepository extends BaseRepository implements ProductVariantAttributeRepositoryInterface
{
    protected $model;
    public function __construct(
        ProductVariantAttribute $model
    ) {
        $this->model = $model;
    }
}
