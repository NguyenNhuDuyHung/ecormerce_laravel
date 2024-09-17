<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use App\Models\System;
/**
 * Class UserService
 * @package App\Services
 */
class SystemRepository extends BaseRepository implements SystemRepositoryInterface
{
    protected $model;

    public function __construct(System $model)
    {
        $this->model = $model;
    }
}
