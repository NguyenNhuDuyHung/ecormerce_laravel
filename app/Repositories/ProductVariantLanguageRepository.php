<?php

namespace App\Repositories;

use App\Models\ProductVariantLanguage;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class ProductVariantService
 * @package App\Services
 */
class ProductVariantLanguageRepository extends BaseRepository implements ProductVariantLanguageRepositoryInterface
{
    protected $model;
    public function __construct(
        ProductVariantLanguage $model
    ) {
        $this->model = $model;
    }
}
