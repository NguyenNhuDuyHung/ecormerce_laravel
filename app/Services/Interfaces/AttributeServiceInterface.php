<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeServiceInterface
{
    public function paginate($request, $languageId);
}
