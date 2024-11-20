<?php

namespace App\Interfaces;

interface OrderRepositoryInterface
{
    public function index(array $queryParams, $filter, array|null $relations = null);

    public function getById($id, array|null $relations = null);

    public function store(array $data);

    public function update($id, array $data);

    public function delete($id);
}
