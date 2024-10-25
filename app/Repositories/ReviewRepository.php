<?php

namespace App\Repositories;

use App\Models\Review;
use App\Interfaces\ReviewRepositoryInterface;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function index()
    {
        return Review::all();
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
