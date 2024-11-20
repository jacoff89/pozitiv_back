<?php

namespace App\Services;

use App\Http\Filters\OrderFilter;
use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\TripRepositoryInterface;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    private AdditionalServiceRepositoryInterface $additionalServiceRepository;

    private TripRepositoryInterface $tripRepository;

    private OrderFilter $orderFilter;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AdditionalServiceRepositoryInterface $additionalServiceRepository,
        TripRepositoryInterface $tripRepository,
        OrderFilter $orderFilter,
    ) {
        $this->orderRepository = $orderRepository;
        $this->additionalServiceRepository = $additionalServiceRepository;
        $this->tripRepository = $tripRepository;
        $this->orderFilter = $orderFilter;
    }

    public function createOrder($params)
    {

        //Проверим что поездка и переданные доп. услуги существуют
        $trip = $this->tripRepository->getById($params['trip_id'], ['additionalServices']);
        dd($trip->toArray());
    }
}
