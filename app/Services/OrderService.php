<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\TouristRepositoryInterface;
use App\Interfaces\TripRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private OrderRepositoryInterface $orderRepository;

    private TripRepositoryInterface $tripRepository;

    private UserRepositoryInterface $userRepository;

    private TouristRepositoryInterface $touristRepository;

    private UserService $userService;

    private User $user;

    private Trip $trip;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        TripRepositoryInterface $tripRepository,
        UserRepositoryInterface $userRepository,
        TouristRepositoryInterface $touristRepository,
        UserService $userService,
    ) {
        $this->orderRepository = $orderRepository;
        $this->tripRepository = $tripRepository;
        $this->userRepository = $userRepository;
        $this->touristRepository = $touristRepository;
        $this->userService = $userService;
    }

    /**
     * @throws \Exception
     */
    public function createOrder(array $orderParams, array $userParams = null): array
    {
        DB::beginTransaction();
        try {
            $this->trip = $this->tripRepository->getById($orderParams['trip_id'], ['orders']);

            //В случае создания пользователя прибавляем основного туриста
            $touristsCount = ($userParams) ? count($orderParams['tourists']) + 1 : count($orderParams['tourists']);
            $checkPlaces = $this->checkingPlacesForReservations($touristsCount);
            if (!$checkPlaces) throw new \Exception(__('messages.order.not_seats'));

            if ($userParams) {
                $newUserData = $this->createUserAndTourists($userParams, $orderParams['tourists']);
                $orderParams['tourists'] = $newUserData['tourists'];
                $orderParams['user_id'] = $newUserData['user_id'];
            }

            $this->user = $this->userRepository->getById($orderParams['user_id'], ['tourists']);

            if (!$userParams && !$this->checkingTourists($orderParams['tourists'])) {
                throw new \Exception(__('messages.order.no_tourist'));
            }

            $calculateData = $this->calculateAllAmounts(count($orderParams['tourists']), $orderParams['additional_services'] ?? null);

            $orderData = [
                'user_id' => $orderParams['user_id'],
                'trip_id' => $orderParams['trip_id'],
                'comment' => $orderParams['comment'],
                'amount' => $calculateData['costSum'],
                'prepayment' => $calculateData['minCostSum'],
                'bonuses' => $calculateData['bonusesSum'],
                'tourists_count' => $touristsCount,
            ];

            $order = $this->orderRepository->store($orderData);

            $order->tourists()->attach($orderParams['tourists']);

            if (isset($orderParams['additional_services'])) {
                foreach ($orderParams['additional_services'] as $service) {
                    $order->additionalServices()->attach($service['id'], ['count' => $service['count']]);
                }
            }

            DB::commit();

            return [
                'order' => $order,
                'user' => $this->user,
            ];

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * @throws \Exception
     */
    private function createUserAndTourists($userParams, $tourists): array
    {
        $newUserData = $this->userService->createUserWithTourist($userParams);
        $res['user_id'] = $newUserData['id'];
        $newTourists[] = $newUserData['mainTourist']->id;
        foreach ($tourists as $newTourist) {
            $newTourist['user_id'] = $newUserData['id'];
            $newTourists[] = $this->touristRepository->store($newTourist)->id;
        }
        $res['tourists'] = $newTourists;

        return $res;
    }

    private function checkingPlacesForReservations(int $touristsCount): bool
    {
        $touristLimit = $this->trip->tourist_limit;
        $signUpTourists = 0;
        foreach ($this->trip->orders as $order) {
            $signUpTourists += $order->tourists_count;
        }
        $signUpTourists += $touristsCount;

        if ($signUpTourists > $touristLimit) return false;

        return true;
    }

    private function checkingTourists(array $tourists): bool
    {
        foreach ($this->user->tourists as $tourist) {
            $allTouristIds[] = $tourist->id;
        }
        foreach ($tourists as $val) {
            if (!in_array($val, $allTouristIds)) return false;
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    private function calculateAllAmounts(int $touristsCount, array|null $additionalServices): array
    {
        //Посчитаем сумму общую, минимальную и кол-во бонусов с тура
        $costSum = $this->trip->cost * $touristsCount;
        $minCostSum = $this->trip->min_cost * $touristsCount;
        $bonusesSum = $this->trip->bonuses * $touristsCount;

        //Посчитаем сумму, предоплату и бонусы с заказа доп. услуг
        if (isset($additionalServices)) {
            foreach ($additionalServices as $additionalService) {

                // TODO: Ликвидировать костыль
                $dbRes = DB::table('additional_service_trip')
                    ->where('trip_id', $this->trip->id)
                    ->where('additional_service_id', $additionalService['id'])
                    ->first();
                if (!$dbRes) throw new \Exception(__('messages.order.no_service'));

                $costSum += $dbRes->cost * $additionalService['count'];
                $minCostSum += $dbRes->min_сost * $additionalService['count'];
                $bonusesSum += $dbRes->bonuses * $additionalService['count'];

            }
        }

        return [
            'costSum' => $costSum,
            'minCostSum' => $minCostSum,
            'bonusesSum' => $bonusesSum,
        ];
    }
}
