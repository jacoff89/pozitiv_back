<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass as ResponseClass;
use App\Http\Requests\StoreTouristRequest;
use App\Http\Requests\UpdateTouristRequest;
use App\Http\Resources\TouristResource;
use App\Interfaces\TouristRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TouristController extends Controller
{

    private TouristRepositoryInterface $touristRepositoryInterface;

    public function __construct(TouristRepositoryInterface $touristRepositoryInterface)
    {
        $this->touristRepositoryInterface = $touristRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->touristRepositoryInterface->index();

        return ResponseClass::sendResponse(TouristResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTouristRequest $request)
    {
        $details = [
            'name' => $request->name
        ];
        DB::beginTransaction();
        try {
            $trip = $this->touristRepositoryInterface->store($details);

            DB::commit();
            return ResponseClass::sendResponse(new TouristResource($trip), 'Tourist Create Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->touristRepositoryInterface->getById($id);

        return ResponseClass::sendResponse(new TouristResource($trip));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTouristRequest $request, $id)
    {
        $updateDetails = [];
        if ($request->name) $updateDetails['name'] = $request->name;
        if(empty($updateDetails)) {
            return ResponseClass::sendResponse('', 'Update Failed (all fields is empty)', 400);
        }
        DB::beginTransaction();
        try {
            $trip = $this->touristRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ResponseClass::sendResponse(new TouristResource($trip), 'Tourist Update Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->touristRepositoryInterface->delete($id)) {
            return ResponseClass::sendResponse('Tourist Delete Successful', '', 201);
        }
        return ResponseClass::sendResponse('Tourist is missing', '', 404);
    }
}
