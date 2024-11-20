<?php

namespace App\Services;

use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\TripRepositoryInterface;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    private AdditionalServiceRepositoryInterface $additionalServiceRepository;

    private TripRepositoryInterface $tripRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AdditionalServiceRepositoryInterface $additionalServiceRepository,
        TripRepositoryInterface $tripRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->additionalServiceRepository = $additionalServiceRepository;
        $this->tripRepository = $tripRepository;
    }

    public function createOrder()
    {
        dd(111);
    }
}
