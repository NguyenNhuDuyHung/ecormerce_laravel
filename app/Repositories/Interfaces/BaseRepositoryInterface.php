<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all(array $relation = []);
    public function pagination(
        array $column = ['*'],
        array $condition = [],
        int $perpage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = []
    );
    public function findById(int $id);
    public function create(array $payload);
    public function update(int $id, array $payload = []);
    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = []);
    public function delete(int $id);
    public function createPivot($model, array $payload = [], string $relation = '');
}
