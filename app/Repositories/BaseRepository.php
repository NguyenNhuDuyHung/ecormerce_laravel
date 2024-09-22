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
        int $perpage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = []
    ) {
        $query = $this->model->select($column);
        return $query->publish($condition['publish'] ?? null)
            ->keyword($condition['keyword'] ?? null)
            ->customWhere($condition['where'] ?? [])
            ->customWhereRaw($rawQuery['whereRaw'] ?? [])
            ->relationCount($relations ?? null)
            ->relation($relations ?? null)
            ->customJoin($join ?? null)
            ->customOrderBy($orderBy ?? null)
            ->customGroupBy($extend['groupBy'] ?? [])
            ->paginate($perpage)
            ->withQueryString()
            ->withPath(env('APP_URL') . $extend['path']);
    }

    public function create(array $payload = [])
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function all(array $relation = [])
    {
        return $this->model->with($relation)->get();
    }

    public function findById(
        int $modelId,
        array $column = ['*'],
        array $relation = []
    ) {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }

    public function findByCondition($condition = [], $flag = false, $relation = [], $orderBy = ['id', 'DESC'])
    {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $value) {
            $query = $query->where($value[0], $value[1], $value[2]);
        }
        $query->with($relation);
        $query->orderBy($orderBy[0], $orderBy[1]);
        return $flag == false ? $query->first() : $query->get();
    }

    public function update(int $id = 0, array $payload = [])
    {
        $model = $this->findById($id);
        $model->fill($payload);
        $model->save();

        return $model;
    }

    public function updateByWhere($condition = [], array $payload = [])
    {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $value) {
            $query->where($value[0], $value[1], $value[2]);
        }
        return $query->update($payload);
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

    public function forceDeleteByCondition(array $condition = [])
    {
        $query = $this->model->newQuery();
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->forceDelete();
    }

    public function createPivot($model, array $payload = [], string $relation = '')
    {
        return $model->{$relation}()->attach($model->id, $payload);
    }

    // insert nhiều bản ghi
    public function insertBatch(array $payload = [])
    {
        return $this->model->insert($payload);
    }

    // Upsert 
    public function updateOrInsert(array $payload = [], array $condition = [])
    {
        return $this->model->updateOrInsert($condition, $payload);
    }
}
