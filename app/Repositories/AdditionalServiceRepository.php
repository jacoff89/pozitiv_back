<?php

namespace App\Repositories;

use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Models\AdditionalService;

class AdditionalServiceRepository implements AdditionalServiceRepositoryInterface
{
    public function index(array $queryParams, $filter)
    {
        return $filter->apply(AdditionalService::query(), $queryParams)->get();
    }

    public function getById($id)
    {
        return AdditionalService::findOrFail($id);
    }

    public function store(array $data)
    {
        return AdditionalService::create($data);
    }

    public function update($id, array $data)
    {
        AdditionalService::whereId($id)->update($data);
        return AdditionalService::findOrFail($id);
    }

    public function delete($id)
    {
        return AdditionalService::destroy($id);
    }
}
