<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Interfaces\TripRepositoryInterface;
use App\Classes\ApiResponseClass as ResponseClass;
use App\Http\Resources\TripResource;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{

    private TripRepositoryInterface $tripRepositoryInterface;

    public function __construct(TripRepositoryInterface $tripRepositoryInterface)
    {
        $this->tripRepositoryInterface = $tripRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->tripRepositoryInterface->index();

        return ResponseClass::sendResponse(TripResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $details = [
            'cost' => $request->cost,
            'minCost' => $request->minCost,
            'dateStart' => $request->dateStart,
            'dateEnd' => $request->dateEnd,
            'touristLimit' => $request->touristLimit,
            'tour_id' => $request->tour_id,
        ];
        DB::beginTransaction();
        try {
            $trip = $this->tripRepositoryInterface->store($details);

            DB::commit();
            return ResponseClass::sendResponse(new TripResource($trip), 'Trip Create Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->tripRepositoryInterface->getById($id);

        return ResponseClass::sendResponse(new TripResource($trip));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, $id)
    {
        $updateDetails = [];
        if ($request->cost) $updateDetails['cost'] = $request->cost;
        if ($request->minCost) $updateDetails['minCost'] = $request->minCost;
        if ($request->dateStart) $updateDetails['dateStart'] = $request->dateStart;
        if ($request->dateEnd) $updateDetails['dateEnd'] = $request->dateEnd;
        if ($request->touristLimit) $updateDetails['touristLimit'] = $request->touristLimit;
        if ($request->tour_id) $updateDetails['tour_id'] = $request->tour_id;
        if(empty($updateDetails)) {
            return ResponseClass::sendResponse('', 'Update Failed (all fields is empty)', 400);
        }
        DB::beginTransaction();
        try {
            $trip = $this->tripRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ResponseClass::sendResponse(new TripResource($trip), 'Trip Update Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->tripRepositoryInterface->delete($id)) {
            return ResponseClass::sendResponse('Trip Delete Successful', '', 201);
        }
        return ResponseClass::sendResponse('Trip is missing', '', 404);
    }
}
