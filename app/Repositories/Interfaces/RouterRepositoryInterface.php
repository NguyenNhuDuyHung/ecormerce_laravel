<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RouterServiceInterface
 * @package App\Services\Interfaces
 */
interface RouterRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCondition($condition = []);
}
