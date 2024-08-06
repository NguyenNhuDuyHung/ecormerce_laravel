<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface ProvinceRepositoryInterface
{
    public function all();

    public function findById(int $id, array $column = ['*'], array $relation = []);
}
