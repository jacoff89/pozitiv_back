<?php

namespace App\Repositories;

use App\Models\Tourist;
use App\Interfaces\TouristRepositoryInterface;

class TouristRepository implements TouristRepositoryInterface
{
    public function index()
    {
        return Tourist::all();
    }

    public function getById($id)
    {
        return Tourist::findOrFail($id);
    }

    public function store(array $data)
    {
        return Tourist::create($data);
    }

    public function update(array $data, $id)
    {
        Tourist::whereId($id)->update($data);
        return Tourist::findOrFail($id);
    }

    public function delete($id)
    {
        return Tourist::destroy($id);
    }
}
