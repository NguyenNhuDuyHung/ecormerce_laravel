<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete(int $id);
}