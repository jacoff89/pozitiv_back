<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\TripRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    private TripRepositoryInterface $tripRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        TripRepositoryInterface $tripRepository,
        UserRepositoryInterface $userRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->tripRepository = $tripRepository;
        $this->userRepository = $userRepository;
    }

    public function createOrder($params)
    {
        DB::beginTransaction();
        try {
            $trip = $this->tripRepository->getById($params['trip_id'], ['orders']);
            $user = $this->userRepository->getById($params['user_id'], ['tourists']);

            //Проверим есть ли места для бронирования
            $touristLimit = $trip->tourist_limit;
            $signUpTourists = 0;
            foreach ($trip->orders as $order) {
                $signUpTourists += $order->tourists_count;
            }
            $signUpTourists += count($params['tourists']);
            if ($signUpTourists > $touristLimit) throw new \Exception(__('messages.order.not_seats'));

            //Проверим что туристы существуют и привязаны к данному юзеру
            foreach ($user->tourists as $tourist) {
                $allTouristIds[] = $tourist->id;
            }
            foreach ($params['tourists'] as $val) {
                if (!in_array($val, $allTouristIds)) throw new \Exception(__('messages.order.no_tourist'));
            }

            $costSum = $trip->cost * count($params['tourists']);
            $minCostSum = $trip->min_cost * count($params['tourists']);
            $bonusesSum = $trip->bonuses * count($params['tourists']);

            //Посчитаем сумму, предоплату и бонусы с заказа доп. услуг
            if (isset($params['additional_services'])) {
                foreach ($params['additional_services'] as $additional_service) {
                    $res = DB::table('additional_service_trip')
                        ->where('trip_id', $params['trip_id'])
                        ->where('additional_service_id', $additional_service['id'])
                        ->first();
                    if (!$res) throw new \Exception(__('messages.order.no_service'));

                    $costSum += $res->cost * $additional_service['count'];
                    $minCostSum += $res->min_сost * $additional_service['count'];
                    $bonusesSum += $res->bonuses * $additional_service['count'];
                    $additionalServices[$additional_service['id']] = ['count' => $additional_service['count']];
                }
            }

            $orderData = [
                'user_id' => $params['user_id'],
                'trip_id' => $params['trip_id'],
                'comment' => $params['comment'],
                'amount' => $costSum,
                'prepayment' => $minCostSum,
                'bonuses' => $bonusesSum,
                'tourists_count' => count($params['tourists']),
            ];

            $order = $this->orderRepository->store($orderData);

            $order->tourists()->attach($params['tourists']);

            if (isset($additionalServices)) $order->additionalServices()->attach($additionalServices);
            DB::commit();

            return $order;

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
