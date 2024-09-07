<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueServiceInterface
{
    public function paginate($request, $languageId);
}
