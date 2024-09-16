<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueRepositoryInterface extends BaseRepositoryInterface
{
    public function getAttributeCatalogueById(int $id = 0, $language_id = 0);
}
