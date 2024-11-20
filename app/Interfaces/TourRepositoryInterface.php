<?php

namespace App\Interfaces;

interface TourRepositoryInterface
{
    public function index(array $queryParams, $filter, array|null $relations = null);

    public function getById($id, array|null $relations = null);

    public function store(array $data, $planPicture, $images);

    public function update($id, array $data, $planPicture, $images);

    public function delete($id);
}
