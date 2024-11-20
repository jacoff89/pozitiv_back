<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function index(array $queryParams, $filter, array|null $relations = null)
    {
        $orders = Order::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $orders->with($relation);
            }
        }
        return $filter->apply($orders, $queryParams)->get();
    }

    public function getById($id, array|null $relations = null)
    {
        $order = Order::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $order->with($relation);
            }
        }
        return $order->findOrFail($id);
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
