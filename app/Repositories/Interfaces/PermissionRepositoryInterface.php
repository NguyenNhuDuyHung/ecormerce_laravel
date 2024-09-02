<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PermissionServiceInterface
 * @package App\Services\Interfaces
 */
interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function updateByWhere(array $condition = [], array $payload = []);
    public function forceDelete(int $id);
}