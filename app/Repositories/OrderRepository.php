<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function index(array $queryParams, $filter)
    {
        return $filter->apply(Order::with('additionalServices', 'tourists', 'user', 'trip'), $queryParams)->get();
    }

    public function getById($id)
    {
        return Order::with('additionalServices', 'tourists', 'user', 'trip')->findOrFail($id);
    }

    public function store(array $data)
    {
        return Order::create($data);
    }

    public function update($id, array $data)
    {
        Order::whereId($id)->update($data);
        return Order::findOrFail($id);
    }

    public function delete($id)
    {
        return Order::destroy($id);
    }
}
