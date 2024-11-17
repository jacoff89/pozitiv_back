<?php

namespace App\Interfaces;

interface ReviewRepositoryInterface
{
    public function index(array $queryParams, $filter);

    public function getById($id);

    public function store(array $data);

    public function update(array $data, $id);

    public function delete($id);
}
