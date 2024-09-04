<?php

namespace App\Repositories;

use App\Models\Generate;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\GenerateRepositoryInterface;

/**
 * Class GenerateService
 * @package App\Services
 */
class GenerateRepository extends BaseRepository implements GenerateRepositoryInterface
{
    protected $model;
    public function __construct(Generate $model)
    {
        $this->model = $model;
    }

}
