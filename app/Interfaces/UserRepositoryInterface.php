<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function index();

    public function getById($id);

    public function store(array $data);

    public function update($id, array $data);
}
