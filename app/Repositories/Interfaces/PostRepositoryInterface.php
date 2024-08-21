<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostRepositoryInterface extends BaseRepositoryInterface
{
    public function getPostById(int $id, int $language_id);
    public function forceDelete(int $id);
}
