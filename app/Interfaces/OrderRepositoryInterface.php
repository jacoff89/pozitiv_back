<?php

namespace App\Interfaces;

interface OrderRepositoryInterface
{
    public function index();

    public function getById($id);

    public function store(array $data);

    public function update($id, array $data);

    public function delete($id);
}
