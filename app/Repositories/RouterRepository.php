<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Models\Router;
/**
 * Class UserService
 * @package App\Services
 */
class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;

    public function __construct(Router $model)
    {
        $this->model = $model;
    }
}
