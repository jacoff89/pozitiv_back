<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Filters\TouristFilter;
use App\Http\Requests\Tourist\StoreTouristRequest;
use App\Http\Requests\Tourist\UpdateTouristRequest;
use App\Http\Resources\TouristResource;
use App\Interfaces\TouristRepositoryInterface;
use App\Models\Tourist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TouristController extends Controller
{
    private Tourist $tourist;

    private TouristRepositoryInterface $touristRepositoryInterface;

    public function __construct(TouristRepositoryInterface $touristRepositoryInterface, Tourist $tourist)
    {
        $this->touristRepositoryInterface = $touristRepositoryInterface;
        $this->tourist = $tourist;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, TouristFilter $filter, $user_id = null)
    {
        $this->authorize('viewAny', $this->tourist);
        $params = $request->only('user_id');
        if ($user_id && !is_numeric($user_id)) abort(404);
        if ($user_id) $params['user_id'] = (int)$user_id;

        if (!Auth::user()->isAdmin()) $params['user_id'] = Auth::id();

        $data = $this->touristRepositoryInterface->index($params, $filter);

        return JsonResponseHelper::success(TouristResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTouristRequest $request, $user_id = null)
    {
        $this->authorize('create', $this->tourist);
        $params = $request->only('first_name', 'last_name', 'phone', 'user_id');
        if ($user_id) {
            if (!is_numeric($user_id)) abort(404);
            $params['user_id'] = (int)$user_id;
        } elseif (!Auth::user()->isAdmin()) {
            $params['user_id'] = Auth::id();
        } elseif (!isset($params['user_id']) || !is_numeric($params['user_id'])) {
            return JsonResponseHelper::validationError('Поле user_id обязательно для заполнения');
        }

        try {
            $tourist = $this->touristRepositoryInterface->store($params);
            return JsonResponseHelper::success(new TouristResource($tourist), __('messages.tourist.added'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tourist.add_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tourist = $this->touristRepositoryInterface->getById($id);
        $this->authorize('view', $tourist);

        return JsonResponseHelper::success(new TouristResource($tourist));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTouristRequest $request, $id)
    {
        $tourist = $this->touristRepositoryInterface->getById($id);
        $this->authorize('update', $tourist);

        $params = $request->only('first_name', 'last_name', 'phone', 'user_id');
        if(empty($params)) {
            return JsonResponseHelper::error(__('messages.update_err.all_fields_is_empty'), 422);
        }
        try {
            if (!Auth::user()->isAdmin()) unset($params['user_id']);
            $data = $this->touristRepositoryInterface->update($params, $id);
            return JsonResponseHelper::success(new TouristResource($data), __('messages.tourist.updated'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tourist.update_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tourist = $this->touristRepositoryInterface->getById($id);
        $this->authorize('delete', $tourist);
        try {
            $this->touristRepositoryInterface->delete((int)$id);
            return JsonResponseHelper::success('', __('messages.tourist.deleted'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tourist.missing'), 404, $ex->getMessage());
        }
    }
}
