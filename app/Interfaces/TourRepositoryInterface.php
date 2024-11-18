<?php

namespace App\Interfaces;

interface TourRepositoryInterface
{
    public function index(array $queryParams, $filter);

    public function getById($id);

    public function store(array $data, $planPicture, $images);

    public function update($id, array $data, $planPicture, $images);

    public function delete($id);
}
