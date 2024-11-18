<?php

namespace App\Interfaces;

interface ReviewRepositoryInterface
{
    public function index(array $queryParams, $filter);

    public function getById($id);

    public function store(array $data, $image);

    public function update($id, array $data, $image);

    public function delete($id);
}
