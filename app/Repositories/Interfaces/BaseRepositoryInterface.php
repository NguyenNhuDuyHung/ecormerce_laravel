<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all();
    public function pagination(array $column = ['*'], array $condition = [], array $join = [], int $perpage = 1, array $extend = []);
    public function findById(int $id);
    public function create(array $payload);
    public function update(int $id, array $payload = []);

    public function delete(int $id);

}
