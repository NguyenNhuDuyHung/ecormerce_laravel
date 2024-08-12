<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageRepositoryInterface
{
    public function pagination(array $column = ['*'], array $condition = [], array $join = [], int $perpage = 1, array $extend = [], array $relations = []);
    public function findById(int $id);
    public function create(array $payload);
    public function update(int $id, array $payload = []);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
    public function delete(int $id);
    public function forceDelete(int $id);
}