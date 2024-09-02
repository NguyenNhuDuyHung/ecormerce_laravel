<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete(int $id);

    public function getPostCatalogueById(int $id, int $language_id);
}
