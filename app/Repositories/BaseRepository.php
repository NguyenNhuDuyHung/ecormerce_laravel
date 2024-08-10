<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserService
 * @package App\Services
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'],
        array $condition = [],
        array $join = [],
        int $perpage = 1,
        array $extend = [],
        array $relations = []
    ) {
        $query = $this->model->select($column)->where(function ($queryWhere) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $queryWhere->where('name', 'LIKE', '%' . $condition['keyword'] . '%');
            }

            if (isset($condition['publish']) && $condition['publish'] != 0) {
                $queryWhere->where('publish', '=', $condition['publish']);
            }
            return $queryWhere;
        });
        if(!empty($relations)){
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }

        if (!empty($join)) {
            $query->join(...$join);
        }

        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function create(array $payload = [])
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById(int $modelId, array $column = ['*'], array $relation = [])
    {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }

    public function update(int $id, array $payload = [])
    {
        $model = $this->findById($id);
        return $model->update($payload);
    }

    public function updateByWhereIn($whereInField = '', array $whereIn = [], array $payload = [])
    {
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    public function delete(int $id)
    {
        $model = $this->findById($id);
        return $model->delete();
    }

    public function forceDelete(int $id)
    {
        $model = $this->findById($id);
        return $model->forceDelete();
    }
}
