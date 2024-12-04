<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Filters\TripFilter;
use App\Http\Requests\Trip\StoreTripRequest;
use App\Http\Requests\Trip\UpdateTripRequest;
use App\Http\Resources\TripResource;
use App\Interfaces\TripRepositoryInterface;
use App\Models\Trip;
use Illuminate\Foundation\Http\FormRequest;

class TripController extends Controller
{
    private Trip $trip;

    private TripRepositoryInterface $tripRepositoryInterface;

    public function __construct(TripRepositoryInterface $tripRepositoryInterface, Trip $trip)
    {
        $this->tripRepositoryInterface = $tripRepositoryInterface;
        $this->trip = $trip;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, TripFilter $filter, $tour_id = null)
    {
        $params = $request->only('tour_id');
        if ($tour_id && !is_numeric($tour_id)) abort(404);
        if ($tour_id) $params['tour_id'] = (int)$tour_id;
        $data = $this->tripRepositoryInterface->index($params, $filter, ['additionalServices']);

        return JsonResponseHelper::success(TripResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request, $tour_id = null)
    {
        $this->authorize('create', $this->trip);
        $params = $request->only('tour_id', 'cost', 'min_cost', 'date_start', 'date_end', 'tourist_limit', 'bonuses');
        if ($tour_id ) {
            if (!is_numeric($tour_id)) abort(404);
            $params['tour_id'] = (int)$tour_id;
        } elseif (!isset($params['tour_id']) || !is_numeric($params['tour_id'])) {
            return JsonResponseHelper::validationError('Поле tour_id обязательно для заполнения');
        }
        try {
            $trip = $this->tripRepositoryInterface->store($params);
            return JsonResponseHelper::success(new TripResource($trip), __('messages.trip.added'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.tour.add_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->tripRepositoryInterface->getById($id, ['additionalServices']);

        return JsonResponseHelper::success(new TripResource($trip));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, $id)
    {
        $this->authorize('update', $this->trip);
        $params = $request->only('cost', 'min_cost', 'date_start', 'date_end', 'tourist_limit', 'bonuses', 'tour_id');
        if(empty($params)) {
            return JsonResponseHelper::error(__('messages.update_err.all_fields_is_empty'), 422);
        }
        try {
            $trip = $this->tripRepositoryInterface->update((int)$id, $params);
            return JsonResponseHelper::success(new TripResource($trip), __('messages.trip.updated'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.trip.update_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->trip);
        try {
            $this->tripRepositoryInterface->delete((int)$id);
            return JsonResponseHelper::success('', __('messages.trip.deleted'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.trip.missing'), 404, $ex->getMessage());
        }
    }
}
