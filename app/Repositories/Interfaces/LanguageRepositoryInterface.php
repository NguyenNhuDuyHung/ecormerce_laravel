<?php

namespace App\Repositories\Interfaces;

/**
 * Interface LanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageRepositoryInterface extends BaseRepositoryInterface
{
    public function forceDelete(int $id);
}