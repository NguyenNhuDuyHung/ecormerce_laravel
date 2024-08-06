<?php

namespace App\Repositories;

use App\Models\District;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\DistrictRepositoryInterface;

/**
 * Class UserService
 * @package App\Services
 */
class DistrictRepository extends BaseRepository implements DistrictRepositoryInterface
{
    protected $model;
    public function __construct(District $model)
    {
        $this->model = $model;
    }

    public function findDistrictByProvinceId(int $province_id = 0)
    {
        return $this->model->where("province_code", '=', $province_id)->get();
    }

}
