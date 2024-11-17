<?php

namespace App\Repositories;

use App\Models\Review;
use App\Interfaces\ReviewRepositoryInterface;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function index(array $queryParams, $filter)
    {
        return $filter->apply(Review::query(), $queryParams)->get();
    }

    public function getById($id)
    {
        return Review::findOrFail($id);
    }

    public function store(array $data)
    {
        return Review::create($data);
    }

    public function update(array $data, $id)
    {
        Review::whereId($id)->update($data);
        return Review::findOrFail($id);
    }

    public function delete($id)
    {
        return Review::destroy($id);
    }
}
