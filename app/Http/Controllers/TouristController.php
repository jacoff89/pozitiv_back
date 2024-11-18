<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper as ResponseClass;
use App\Http\Requests\Tourist\StoreTouristRequest;
use App\Http\Requests\Tourist\UpdateTouristRequest;
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
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'user_id' => $request->user_id,
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
        if ($request->first_name) $updateDetails['first_name'] = $request->first_name;
        if ($request->last_name) $updateDetails['last_name'] = $request->last_name;
        if ($request->phone) $updateDetails['phone'] = $request->phone;
        if ($request->user_id) $updateDetails['user_id'] = $request->user_id;
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
