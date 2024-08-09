<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete(int $id);
}
