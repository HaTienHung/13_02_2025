<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function findById($id, array $with = []);

    // public function findAllById($id, array $with = []);

    public function findAllBy(array $condition = [], array $with = []);

    public function deleteBy(array $condition = []);

    public function findBy($field, $value, $columns = array('*'));

    public function createOrUpdate($data, array $condition = []);
}
