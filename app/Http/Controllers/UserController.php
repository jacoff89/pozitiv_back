<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\TouristRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private TouristRepositoryInterface $touristRepositoryInterface;

    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface, TouristRepositoryInterface $touristRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->touristRepositoryInterface = $touristRepositoryInterface;
    }

    public function getCurrent()
    {
        if (!Auth::user()) abort(401);
        $user = $this->userRepositoryInterface->getById(Auth::id());
        return JsonResponseHelper::success(new UserResource($user));
    }

    public function getAll()
    {
        if (!Auth::user()->isAdmin()) abort(401);
        $users = $this->userRepositoryInterface->index();
        return JsonResponseHelper::success(UserResource::collection($users));
    }

    public function register(StoreUserRequest $request)
    {
        $userParams = $request->only('email');
        $userParams['password'] = Hash::make($request->password);

        $touristParams = $request->only('first_name', 'last_name', 'phone');

        DB::beginTransaction();
        try {
            $user = $this->userRepositoryInterface->store($userParams);
            $touristParams['user_id'] = $user->id;

            $tourist = $this->touristRepositoryInterface->store($touristParams);
            $this->userRepositoryInterface->update($user->id, ['main_tourist_id' => $tourist->id]);
            DB::commit();

            return JsonResponseHelper::success(['token' => $user->createToken("WEB APP")->plainTextToken], __('messages.user.added'), 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return JsonResponseHelper::error(__('messages.user.add_err'), 500, $th->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validator->fails()) return JsonResponseHelper::validationError($validator->errors());

            if (!Auth::guard('web')->attempt($request->only(['email', 'password']))) {
                return JsonResponseHelper::error(__('messages.user.does_not_match'), 401);
            }

            $user = User::where('email', $request->email)->first();

            return JsonResponseHelper::success(['token' => $user->createToken("WEB APP")->plainTextToken], __('messages.user.logged'));

        } catch (\Throwable $th) {
            return JsonResponseHelper::error(__('messages.user.login_err'), 500, $th->getMessage());
        }
    }

    public function logout()
    {
        if(!Auth::user()) return JsonResponseHelper::error(__('messages.user.already_logout'), 401);

        Auth::user()->currentAccessToken()->delete();
        return JsonResponseHelper::success('', __('messages.user.logout'));
    }
}
