<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $params = $request->only('first_name', 'last_name', 'phone', 'email');
        $params['password'] = Hash::make($request->password);

        try {
            $user = User::create($params);
            return JsonResponseHelper::success(['token' => $user->createToken("WEB APP")->plainTextToken], __('messages.user.added'), 201);

        } catch (\Throwable $th) {
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
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
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
