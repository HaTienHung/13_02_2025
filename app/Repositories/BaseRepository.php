<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
  protected $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function all()
  {
    return $this->model->all();
  }

  public function find($id)
  {
    return $this->model->findOrFail($id);
  }

  public function create(array $data)
  {
    return $this->model->create($data);
  }

  public function update($id, array $data)
  {
    $record = $this->find($id);
    if ($record) {
      $record->update($data);
      return $record;
    }
    return null;
  }

  public function delete($id)
  {
    $record = $this->find($id);
    if ($record) {
      return $record->delete();
    }
    return false;
  }
  public function findOneBy(array $conditions)
  {
    return $this->model->where($conditions)->first();
  }
  public function findBy(array $conditions)
  {
    $query = $this->model->query();

    foreach ($conditions as $key => $value) {
      if (is_array($value)) {
        $query->whereIn($key, $value);
      } else {
        $query->where($key, $value);
      }
    }

    return $query->get();
  }
}
