<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function index()
    {
        return Order::all();
    }

    public function getById($id)
    {
        return Order::findOrFail($id);
    }

    public function store(array $data)
    {
        return Order::create($data);
    }

    public function update(array $data, $id)
    {
        Order::whereId($id)->update($data);
        return Order::findOrFail($id);
    }

    public function delete($id)
    {
        return Order::destroy($id);
    }
}
