<?php

namespace App\Services;

use App\Interfaces\TouristRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private TouristRepositoryInterface $touristRepositoryInterface;

    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        TouristRepositoryInterface $touristRepositoryInterface
    ) {
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->touristRepositoryInterface = $touristRepositoryInterface;
    }

    /**
     * @throws \Exception
     */
    public function createUserWithTourist($params)
    {
        $userParams = [
            'email' => $params['email'],
            'password' => Hash::make($params['password']),
        ];

        $touristParams = [
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'phone' => $params['phone'],
        ];

        DB::beginTransaction();
        try {
            $user = $this->userRepositoryInterface->store($userParams);
            $touristParams['user_id'] = $user->id;

            $mainTourist = $this->touristRepositoryInterface->store($touristParams);
            $this->userRepositoryInterface->update($user->id, ['main_tourist_id' => $mainTourist->id]);

            DB::commit();

            return $this->userRepositoryInterface->getById($user->id);

        } catch (\Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
