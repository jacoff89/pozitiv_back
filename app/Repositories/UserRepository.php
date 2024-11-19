<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function index()
    {
        return User::with('mainTourist')->get();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function store(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        User::whereId($id)->update($data);
        return User::findOrFail($id);
    }
}
