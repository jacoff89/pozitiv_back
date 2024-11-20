<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Interfaces\TripRepositoryInterface;

class TripRepository implements TripRepositoryInterface
{
    public function index(array $queryParams, $filter, array|null $relations = null)
    {
        $trip = Trip::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $trip->with($relation);
            }
        }
        return $filter->apply($trip, $queryParams)->get();
    }

    public function getById($id, array|null $relations = null)
    {
        $trip = Trip::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $trip->with($relation);
            }
        }
        return $trip->findOrFail($id);
    }

    public function store(array $data)
    {
        return Trip::create($data);
    }

    public function update($id, array $data)
    {
        Trip::whereId($id)->update($data);
        return Trip::findOrFail($id);
    }

    public function delete($id)
    {
        return Trip::destroy($id);
    }
}
