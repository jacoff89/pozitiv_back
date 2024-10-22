<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\Trip;
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

        return ResponseClass::sendResponse(TripResource::collection($data), '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $details = [
            'price' => $request->price,
            'places_count' => $request->places_count
        ];
        DB::beginTransaction();
        try {
            $trip = $this->tripRepositoryInterface->store($details);

            DB::commit();
            return ResponseClass::sendResponse(new TripResource($trip), 'Product Create Successful', 201);

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

        return ResponseClass::sendResponse(new TripResource($trip), '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, $id)
    {
        $updateDetails = [
            'price' => $request->price,
            'places_count' => $request->places_count
        ];
        DB::beginTransaction();
        try {
            $trip = $this->tripRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ResponseClass::sendResponse('Product Update Successful', '', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->tripRepositoryInterface->delete($id);

        return ResponseClass::sendResponse('Product Delete Successful','',204);
    }
}
