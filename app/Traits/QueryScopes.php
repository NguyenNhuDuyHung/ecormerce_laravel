<?php

namespace App\Traits;


trait QueryScopes
{
    public function __construct()
    {

    }
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        return $query;
    }

    public function scopePublish($query, $publish)
    {
        if (!empty($publish) && $publish != 0) {
            $query->where('publish', '=', $publish);
        }

        return $query;
    }

    public function scopeCustomWhere($query, $where = [])
    {
        if (count($where)) {
            foreach ($where as $key => $value) {
                $query->where($value[0], $value[1], $value[2]);
            }
        }

        return $query;
    }

    public function scopeCustomWhereRaw($query, $rawQuery = [])
    {
        if (count($rawQuery)) {
            foreach ($rawQuery as $key => $value) {
                $query->whereRaw($value[0], $value[1]);
            }
        }

        return $query;
    }

    public function scopeRelationCount($query, $relations)
    {
        if (!empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }

        return $query;
    }

    public function scopeRelation($query, $relation)
    {
        if (!empty($relation)) {
            foreach ($relation as $item) {
                $query->with($item);
            }
        }

        return $query;
    }

    public function scopeCustomJoin($query, $join = [])
    {
        if (!empty($join)) {
            foreach ($join as $key => $value) {
                $query->join($value[0], $value[1], $value[2], $value[3]);
            }
        }

        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy)
    {
        if (isset($orderBy) && is_array($orderBy) && !empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }

        return $query;
    }

    public function scopeCustomGroupBy($query, $groupBy = []) {
        if(!empty($groupBy)) {
            $query->groupBy($groupBy);
        }

        return $query;
    }
}
