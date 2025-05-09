<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Filters\UserFilter;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private UserService $userService;

    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(
        UserService $userService,
        UserRepositoryInterface $userRepositoryInterface,
    ) {
        $this->userService = $userService;
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function getCurrent()
    {
        if (!Auth::user()) abort(401);
        $user = $this->userRepositoryInterface->getById(Auth::id());
        return JsonResponseHelper::success(new UserResource($user));
    }

    public function getAll(FormRequest $request, UserFilter $filter)
    {
        $params = $request->only('email');
        if (!Auth::user() || !Auth::user()->isAdmin()) abort(401);
        $users = $this->userRepositoryInterface->index($params, $filter);

        return JsonResponseHelper::success(UserResource::collection($users));
    }

    public function register(StoreUserRequest $request)
    {
        $params = $request->only('email', 'password', 'first_name', 'last_name', 'phone');

        try {
            $user = $this->userService->createUserWithTourist($params);
            $token = $user->createToken("WEB APP")->plainTextToken;
            $userResource = (new UserResource($user))->additional(['token' => $token]);

            return JsonResponseHelper::success($userResource, __('messages.user.added'), 201);


        } catch (\Exception $th) {
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

            $user = User::where('email', $request->email)->with('mainTourist')->first();
            $token = $user->createToken("WEB APP")->plainTextToken;
            $userResource = (new UserResource($user))->additional(['token' => $token]);

            return JsonResponseHelper::success($userResource, __('messages.user.logged'));

        } catch (\Exception $th) {
            return JsonResponseHelper::error(__('messages.user.login_err'), 500, $th->getMessage());
        }
    }

    public function logout()
    {
        if (!Auth::user()) return JsonResponseHelper::error(__('messages.user.already_logout'), 401);

        Auth::user()->currentAccessToken()->delete();
        return JsonResponseHelper::success('', __('messages.user.logout'));
    }
}
