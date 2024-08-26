<?php

namespace App\Repositories\Interfaces;

/**
 * Interface LanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageRepositoryInterface extends BaseRepositoryInterface
{
    public function updateByWhere(array $condition = [], array $payload = []);
    public function forceDelete(int $id);
}