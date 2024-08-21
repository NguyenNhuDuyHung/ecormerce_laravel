<?php

namespace App\Repositories;

use App\Models\Language;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface;

/**
 * Class UserService
 * @package App\Services
 */
class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    protected $model;
    public function __construct(Language $model)
    {
        $this->model = $model;
    }
}
