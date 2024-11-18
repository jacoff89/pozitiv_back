<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Filters\TourFilter;
use App\Http\Requests\Tour\StoreTourRequest;
use App\Http\Requests\Tour\UpdateTourRequest;
use App\Http\Resources\TourResource;
use App\Interfaces\TourRepositoryInterface;
use App\Models\Tour;
use Illuminate\Foundation\Http\FormRequest;

class TourController extends Controller
{
    private Tour $tour;
    private TourRepositoryInterface $tourRepositoryInterface;

    public function __construct(TourRepositoryInterface $tourRepositoryInterface, Tour $tour)
    {
        $this->tourRepositoryInterface = $tourRepositoryInterface;
        $this->tour = $tour;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, TourFilter $filter)
    {
        $params = $request->only('name');
        $data = $this->tourRepositoryInterface->index($params, $filter);

        return JsonResponseHelper::success(TourResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourRequest $request)
    {
        $params = $request->only('name', 'description', 'duration', 'place', 'plan', 'season');

        try {
            $tour = $this->tourRepositoryInterface->store($params, $request->plan_picture, $request->images);
            return JsonResponseHelper::success(new TourResource($tour), __('messages.tour.added'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tour.add_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->tourRepositoryInterface->getById($id);

        return JsonResponseHelper::success(new TourResource($trip));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTourRequest $request, $id)
    {
        $params = $request->only('name', 'description', 'duration', 'place', 'plan', 'season');

        if(empty($params) && !$request->plan_picture && !$request->images) {
            return JsonResponseHelper::error(__('messages.update_err.all_fields_is_empty'), 422);
        }
        try {
            $tour = $this->tourRepositoryInterface->update((int)$id, $params, $request->plan_picture, $request->images);
            return JsonResponseHelper::success(new TourResource($tour), __('messages.tour.updated'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tour.update_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->tour);
        try {
            $this->tourRepositoryInterface->delete((int)$id);
            return JsonResponseHelper::success('', __('messages.tour.deleted'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tour.missing'), 404, $ex->getMessage());
        }
    }
}
