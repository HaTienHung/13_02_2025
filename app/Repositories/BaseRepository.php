<?php

namespace App\Repositories;

use App\Enums\Constant;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFace;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var
     */
    protected $model;

    /**
     * @var Model
     */
    protected $originalModel;

    /**
     * @var App
     */
    private $app;

    /**
     * @var \stdClass
     */
    protected $REQUEST;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->originalModel = $model;
//        $this->initiate($request);
    }

    private function initiate(Request $request): void
    {
        if ($this->USER = AuthFace::user()) {
            $this->isLogin = true;
        };
        // Set global Parameter
//        $this->current_language($request);
        $this->PERPAGE = $request->input('perpage', Constant::PER_PAGE);
        $this->PAGE = $request->input('page', 1);
        $this->REQUEST = $request->toArray();
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }
    public function all()
    {
        return $this->model->all();
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

    public function find($id)
    {
        return $this->model->findorFail($id);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }

    public function findById($id, array $with = [])
    {
        $data = $this->make($with)->where('id', $id);

        $this->resetModel();

        return $data->firstOrFail();
    }

    public function make(array $with = [])
    {
        if (!empty($with)) {
            $this->model = $this->model->with($with);
        }
        return $this->model;
    }

    public function resetModel()
    {
        $this->model = new $this->originalModel();
        return $this;
    }

    public function findAllById($id, array $with = [])
    {
        $data = $this->make($with)->where('id', $id);

        $this->resetModel();

        return $data->get();
    }

    public function deleteBy(array $condition = [])
    {
        $this->applyConditions($condition);
        $data = $this->model->get();
        if (empty($data)) {
            return false;
        }
        foreach ($data as $item) {
            $item->delete();
        }
        $this->resetModel();
        return true;
    }

    protected function applyConditions(array $where, &$model = null)
    {
        if (!$model) {
            $newModel = $this->model;
        } else {
            $newModel = $model;
        }

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $conditions, $val] = $value;
                switch (strtoupper($conditions)) {
                    case 'IN':
                        $newModel = $newModel->whereIn($field, $val);
                        break;
                    case 'NOT IN':
                        $newModel = $newModel->whereNotIn($field, $val);
                        break;
                    default:
                        $newModel = $newModel->where($field, $conditions, $val);
                        break;
                }
            } else {
                $newModel = $newModel->where($field, $value);
            }
        }

        if (!$model) {
            $this->model = $newModel;
        } else {
            $model = $newModel;
        }
    }

    public function delete($id)
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    public function findAllBy(array $condition = [], array $with = [])
    {
        $this->applyConditions($condition);
        $this->make($with);
        $data = $this->model->get();
        $this->resetModel();
        return $data;
    }

    public function createOrUpdate($data, array $condition = [])
    {
        /**
         * @var Model $item
         */
        if (is_array($data)) {
            if (empty($condition)) {
                $item = new $this->model(); //Khoi tao instance cho chinh model do
            } else {
                $item = $this->getFirstBy($condition);
            }

            if (empty($item)) {
                $item = new $this->model(); // Truong hop tao moi !!!!
            }

            $item = $item->fill($data);
        } else if ($data instanceof Model) {
            $item = $data; //Khong can khoi tao instance
        } else {
            return false;
        }
        $this->resetModel();

        if ($item->save()) {
            return $item;
        }
        return false;
    }

    public function getFirstBy(array $condition = [], array $select = ['*'], array $with = [])
    {
        $this->make($with);
        $this->applyConditions($condition);
        if (!empty($select)) {
            $data = $this->model->select($select);
        } else {
            $data = $this->model->select('*');
        }
        $this->resetModel();
        return $data->first();
    }
}
