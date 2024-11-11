<?php

namespace App\Repositories;

use App\Models\Tour;
use App\Interfaces\TourRepositoryInterface;

class TourRepository implements TourRepositoryInterface
{
    public function index()
    {
        return Tour::with('trips')->get();
    }

    public function getById($id)
    {
        return Tour::findOrFail($id);
    }

    public function store(array $data)
    {
        return Tour::create($data);
    }

    public function update(array $data, $id)
    {
        Tour::whereId($id)->update($data);
        return Tour::findOrFail($id);
    }

    public function delete($id)
    {
        return Tour::destroy($id);
    }
}
