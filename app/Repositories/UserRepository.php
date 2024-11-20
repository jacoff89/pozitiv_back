<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function index(array $queryParams, $filter, array|null $relations = null)
    {
        $users = User::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $users->with($relation);
            }
        }
        return $filter->apply($users, $queryParams)->get();
    }

    public function getById($id, array|null $relations = null)
    {
        $user = User::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $user->with($relation);
            }
        }
        return $user->findOrFail($id);
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
