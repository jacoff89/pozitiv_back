<?php

namespace App\Interfaces;

interface TouristRepositoryInterface
{
    public function index(array $queryParams, $filter);

    public function getById($id);

    public function store(array $data);

    public function update($id, array $data);

    public function delete($id);
}
