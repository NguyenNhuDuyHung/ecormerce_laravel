<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate($request, $languageId);
}
