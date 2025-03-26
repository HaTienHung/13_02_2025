<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Str;


trait FilterTrait
{

    protected $condition = ['RANGE', 'IN', 'NOT IN'];


    public function scopeFilter(Builder $builder, ?string $filters)
    {
        $filters = json_decode($filters, true);
        return $builder->when(!empty($filters), function ($query) use ($filters) {
            return $query->where(function ($query) use ($filters) {
                foreach ($filters as $key => $value) {
                    $this->applyFilterCondition($query, $key, $value);
                }
            });
        });
    }

    private function applyFilterCondition($query, $key, $value): void
    {
        list($column, $condition) = $this->parseKey($key);

        // Kiểm tra xem có bảng liên quan không (dựa vào dấu ".")
        if (str_contains($column, '.')) {
            list($relation, $relatedColumn) = explode('.', $column, 2);

            $query->whereHas($relation, function ($subQuery) use ($relatedColumn, $condition, $value) {
                if (is_array($value)) {
                    $this->applyArrayFilterCondition($subQuery, $relatedColumn, $condition, $value);
                } else {
                    $subQuery->where($relatedColumn, $value);
                }
            });
        } else {
            // Nếu không có bảng liên quan, lọc như bình thường
            if (is_array($value)) {
                $this->applyArrayFilterCondition($query, $column, $condition, $value);
            } else {
                $query->where($column, $value);
            }
        }
    }

    private function parseKey($key): array
    {
        $keyParts = explode('_', $key);
        $issetCondition = in_array(end($keyParts), $this->condition);
        $condition = $issetCondition ? array_pop($keyParts) : "WHERE";
        $column = implode('_', $keyParts);
        return [$column, $condition];
    }

    private function applyArrayFilterCondition($query, $column, $condition, $value): void
    {
        switch (strtoupper($condition)) {
            case "RANGE":
                $query->whereBetween($column, $value);
                break;
            case "IN":
                $query->whereIn($column, $value);
                break;
            case "NOT IN":
                $query->whereNotIn($column, $value);
                break;
            default:
                break;
        }
    }

    public function scopeSearch(Builder $builder, ?array $searchFields = [], ?string $search = '')
    {
        return $builder->when(!empty($searchFields) && $search, function ($query) use ($searchFields, $search) {
            return $query->where(function ($query) use ($searchFields, $search) {
                foreach ($searchFields as $searchable) {
                    $this->applySearchCondition($query, $searchable, $search);
                }
            });
        });
    }

    public function applySearchCondition($query, $searchable, $search): void
    {
        if (str_contains($searchable, '.')) {
            list($relation, $column) = $this->getRelationAndColumn($searchable);
            $query->orWhereRelation($relation, $column, 'like', "%$search%");
        } else {
            $query->orWhere($searchable, 'like', "%$search%");
        }
    }

    public function getRelationAndColumn($searchable): array
    {
        $relation = Str::beforeLast($searchable, '.');
        $column = Str::afterLast($searchable, '.');

        return [$relation, $column];
    }

    public function scopeSort(Builder $builder, ?array $options = [], ?string $alisaTable = ''): Builder
    {
        if (!empty($options)) {
            foreach ($options as $sort) {
                $sortDirection = $sort[0] === '-' ? 'DESC' : 'ASC';
                $sortByColumn = preg_replace('/^[+-]/', '', $sort);
                [$alias, $sortByColumn] = preg_match('/\.(?=[A-Za-z])/', $sortByColumn) ? explode('.', $sortByColumn) : [$alisaTable, $sortByColumn];
                $builder->orderBy($alisaTable ? "{$alias}{$sortByColumn}" : $sortByColumn, $sortDirection);
            }
        }
        return $builder;
    }
}
